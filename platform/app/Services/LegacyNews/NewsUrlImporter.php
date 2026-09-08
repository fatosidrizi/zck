<?php

namespace App\Services\LegacyNews;

use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

/**
 * Turns one kryeministri.rks-gov.net link into a draft News record.
 *
 * Each link is independent: a failure leaves nothing half-written, and a link
 * that was already imported is reported as skipped rather than duplicated.
 */
class NewsUrlImporter
{
    public const CATEGORIES = ['news', 'bulletin', 'report'];

    private const MAX_IMAGE_BYTES = 10 * 1024 * 1024;

    public function __construct(private readonly KryeministriArticleFetcher $fetcher) {}

    public function import(string $url, string $category, User $author): ImportResult
    {
        try {
            $normalized = KryeministriArticleFetcher::normalizeUrl($url);
        } catch (InvalidArgumentException $e) {
            return ImportResult::failed(trim($url), $e->getMessage());
        }

        if (! in_array($category, self::CATEGORIES, true)) {
            return ImportResult::failed($normalized, "Unknown category \"{$category}\".");
        }

        $existing = News::where('source_url', $normalized)->first();

        if ($existing) {
            return ImportResult::skipped($normalized, 'already imported', $existing);
        }

        $storedImage = null;

        try {
            $article = $this->fetcher->fetch($normalized);

            if (! $article->hasContent()) {
                return ImportResult::failed($normalized, 'No article title found on the page.');
            }

            $slug = $this->uniqueSlug($article);
            $storedImage = $article->imageUrl ? $this->storeImage($article->imageUrl, $slug) : null;

            $news = DB::transaction(fn () => News::create([
                'title' => $article->title,
                'slug' => $slug,
                'body' => $article->body,
                'image' => $storedImage,
                'source_url' => $normalized,
                'category' => $category,
                'status' => 'draft',
                'published_at' => $article->publishedAt ?? now(),
                'author_id' => $author->id,
            ]));

            $note = ($article->imageUrl && $storedImage === null)
                ? 'imported without cover image (download failed)'
                : null;

            return ImportResult::created($normalized, $news, $note);
        } catch (Throwable $e) {
            if ($storedImage) {
                Storage::disk('public')->delete($storedImage);
            }

            Log::warning('Legacy news import failed', ['url' => $normalized, 'error' => $e->getMessage()]);

            return ImportResult::failed($normalized, $e->getMessage());
        }
    }

    private function uniqueSlug(FetchedArticle $article): string
    {
        $source = $article->title['sq'] ?? $article->title['en'] ?? reset($article->title);
        $base = Str::limit(Str::slug((string) $source), 180, '');

        if ($base === '') {
            $base = 'article-'.substr(sha1($article->sourceUrl), 0, 8);
        }

        $slug = $base;

        for ($i = 2; News::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    /** Downloads the cover image onto the public disk; null when anything about it is off. */
    private function storeImage(string $imageUrl, string $slug): ?string
    {
        try {
            $response = Http::withUserAgent(KryeministriArticleFetcher::USER_AGENT)
                ->timeout(30)
                ->retry(2, 500, throw: false)
                ->get($imageUrl);

            if (! $response->successful()) {
                return null;
            }

            $contentType = strtolower(explode(';', (string) $response->header('Content-Type'))[0]);
            $extension = match ($contentType) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
                default => null,
            };

            if ($extension === null) {
                $fromUrl = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                $extension = in_array($fromUrl, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? ($fromUrl === 'jpeg' ? 'jpg' : $fromUrl) : null;
            }

            $bytes = $response->body();

            if ($extension === null || $bytes === '' || strlen($bytes) > self::MAX_IMAGE_BYTES) {
                return null;
            }

            // Same naming as the earlier legacy import: slug plus a hash of the source.
            $path = 'news/'.Str::limit($slug, 120, '').'-'.substr(sha1($imageUrl), 0, 8).'.'.$extension;

            Storage::disk('public')->put($path, $bytes);

            return $path;
        } catch (Throwable $e) {
            Log::warning('Legacy news import: cover image download failed', ['url' => $imageUrl, 'error' => $e->getMessage()]);

            return null;
        }
    }
}
