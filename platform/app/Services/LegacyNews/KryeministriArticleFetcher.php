<?php

namespace App\Services\LegacyNews;

use Carbon\CarbonImmutable;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Reads an article off kryeministri.rks-gov.net (WordPress + WPML + Elementor
 * + Yoast) and returns it split per app locale.
 *
 * The site's REST API answers 403 to everything, so this parses the public
 * HTML instead: Yoast's JSON-LD for title/date/image, the Elementor
 * "theme-post-content" widget for the body, and the WPML language switcher for
 * the URLs of the other language versions (older posts use a different slug
 * per language, so those must never be guessed by prefixing /en/ or /sr/).
 */
class KryeministriArticleFetcher
{
    public const HOST = 'kryeministri.rks-gov.net';

    public const USER_AGENT = 'Mozilla/5.0 (compatible; ZCK-Importer/1.0; +https://zck.rks-gov.net)';

    /** WPML language codes on the old site => locales in this app. */
    private const WPML_TO_APP = ['sq' => 'sq', 'en' => 'en', 'sr' => 'sr'];

    /** Tags the news RichEditor can round-trip; everything else is unwrapped or dropped. */
    private const KEEP_TAGS = ['p', 'h2', 'h3', 'h4', 'strong', 'b', 'em', 'i', 'u', 's', 'a', 'ul', 'ol', 'li', 'blockquote', 'br'];

    /** Tags removed together with their content. */
    private const DROP_TAGS = ['script', 'style', 'noscript', 'iframe', 'figure', 'img', 'picture', 'svg', 'video', 'audio', 'form', 'button', 'template'];

    public function fetch(string $url): FetchedArticle
    {
        $url = self::normalizeUrl($url);
        $page = $this->fetchPage($url);

        $titles = [];
        $bodies = [];
        $primaryLocale = $page->locale ?? 'sq';
        $titles[$primaryLocale] = $page->title;
        $bodies[$primaryLocale] = $page->body;

        foreach ($page->translations as $lang => $translationUrl) {
            $locale = self::WPML_TO_APP[$lang] ?? null;

            if ($locale === null || isset($titles[$locale]) || $translationUrl === $url) {
                continue;
            }

            try {
                $translation = $this->fetchPage($translationUrl);
            } catch (Throwable $e) {
                // A missing translation is not a reason to lose the article;
                // the public site already labels single-language records.
                Log::warning('Legacy news import: translation fetch failed', [
                    'url' => $translationUrl,
                    'error' => $e->getMessage(),
                ]);

                continue;
            }

            if (trim($translation->title) === '' && trim($translation->body) === '') {
                continue;
            }

            $titles[$locale] = $translation->title;
            $bodies[$locale] = $translation->body;
        }

        return new FetchedArticle(
            sourceUrl: $url,
            title: array_filter($titles, fn ($t) => trim($t) !== ''),
            body: array_filter($bodies, fn ($b) => trim($b) !== ''),
            imageUrl: $page->imageUrl,
            publishedAt: $page->publishedAt,
        );
    }

    /**
     * Canonical https URL with a trailing slash and no query/fragment.
     *
     * @throws InvalidArgumentException when the link is not an article on the expected host
     */
    public static function normalizeUrl(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);

        if ($parts === false || empty($parts['host'])) {
            throw new InvalidArgumentException("Not a valid link: {$url}");
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host']));

        if ($host !== self::HOST) {
            throw new InvalidArgumentException('Only links from '.self::HOST.' can be imported.');
        }

        $path = $parts['path'] ?? '/';
        $path = '/'.ltrim($path, '/');

        if (! str_ends_with($path, '/')) {
            $path .= '/';
        }

        return 'https://'.self::HOST.$path;
    }

    protected function fetchPage(string $url): ParsedPage
    {
        $response = Http::withUserAgent(self::USER_AGENT)
            ->timeout(20)
            ->retry(2, 500, throw: false)
            ->get($url);

        if (! $response->successful()) {
            throw new RuntimeException("HTTP {$response->status()} when fetching {$url}");
        }

        return $this->parsePage($response->body(), $url);
    }

    public function parsePage(string $html, string $url): ParsedPage
    {
        $doc = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        // The encoding prolog is the reliable way to make DOMDocument treat the
        // input as UTF-8; the site's Albanian and Serbian text depends on it.
        $doc->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($doc);
        $graph = $this->jsonLdGraph($xpath);

        return new ParsedPage(
            url: $url,
            locale: $this->pageLocale($doc),
            title: $this->title($xpath, $graph),
            body: $this->body($xpath),
            imageUrl: $this->imageUrl($xpath, $graph),
            publishedAt: $this->publishedAt($xpath, $graph),
            translations: $this->translations($xpath, $url),
        );
    }

    private function pageLocale(DOMDocument $doc): ?string
    {
        $lang = strtolower((string) $doc->documentElement?->getAttribute('lang'));
        $lang = explode('-', $lang)[0] ?? '';

        return self::WPML_TO_APP[$lang] ?? null;
    }

    /** @return array<int, array<string, mixed>> every node of every Yoast @graph on the page */
    private function jsonLdGraph(DOMXPath $xpath): array
    {
        $nodes = [];

        foreach ($xpath->query('//script[@type="application/ld+json"]') as $script) {
            $decoded = json_decode(trim($script->textContent), true);

            if (! is_array($decoded)) {
                continue;
            }

            foreach ($decoded['@graph'] ?? [$decoded] as $node) {
                if (is_array($node)) {
                    $nodes[] = $node;
                }
            }
        }

        return $nodes;
    }

    private function graphNode(array $graph, string $type): ?array
    {
        foreach ($graph as $node) {
            $types = (array) ($node['@type'] ?? []);

            if (in_array($type, $types, true)) {
                return $node;
            }
        }

        return null;
    }

    private function title(DOMXPath $xpath, array $graph): string
    {
        foreach (['NewsArticle', 'Article'] as $type) {
            $headline = $this->graphNode($graph, $type)['headline'] ?? null;

            if (is_string($headline) && trim($headline) !== '') {
                return $this->cleanText($headline);
            }
        }

        $h1 = $xpath->query('//h1')->item(0);

        if ($h1 && trim($h1->textContent) !== '') {
            return $this->cleanText($h1->textContent);
        }

        $og = $this->meta($xpath, 'og:title');

        // Yoast appends " - Kryeministri" to the social title.
        return $this->cleanText(preg_replace('/\s+[-–|]\s+Kryeministri$/u', '', (string) $og));
    }

    private function publishedAt(DOMXPath $xpath, array $graph): ?CarbonImmutable
    {
        $candidates = [];

        foreach ($graph as $node) {
            if (! empty($node['datePublished'])) {
                $candidates[] = $node['datePublished'];
            }
        }

        $candidates[] = $this->meta($xpath, 'article:published_time');
        $candidates[] = $this->meta($xpath, 'article:modified_time');

        foreach ($candidates as $candidate) {
            if (! is_string($candidate) || trim($candidate) === '') {
                continue;
            }

            try {
                return CarbonImmutable::parse($candidate);
            } catch (Throwable) {
                continue;
            }
        }

        return null;
    }

    private function imageUrl(DOMXPath $xpath, array $graph): ?string
    {
        foreach ($graph as $node) {
            $id = (string) ($node['@id'] ?? '');

            if (str_ends_with($id, '#primaryimage')) {
                $candidate = $node['contentUrl'] ?? $node['url'] ?? null;

                if (is_string($candidate) && $candidate !== '') {
                    return $candidate;
                }
            }
        }

        $og = $this->meta($xpath, 'og:image');

        return $og !== null && $og !== '' ? $og : null;
    }

    /** @return array<string, string> */
    private function translations(DOMXPath $xpath, string $currentUrl): array
    {
        $links = [];

        foreach ($xpath->query('//a[contains(concat(" ", normalize-space(@class), " "), " wpml-ls-link ")][@hreflang]') as $anchor) {
            /** @var DOMElement $anchor */
            $lang = strtolower(explode('-', $anchor->getAttribute('hreflang'))[0]);
            $href = $this->absoluteUrl($anchor->getAttribute('href'));

            if ($lang !== '' && $href !== null && ! isset($links[$lang])) {
                $links[$lang] = $href;
            }
        }

        return $links;
    }

    private function body(DOMXPath $xpath): string
    {
        $queries = [
            '//*[contains(concat(" ", normalize-space(@class), " "), " elementor-widget-theme-post-content ")]//*[contains(concat(" ", normalize-space(@class), " "), " elementor-widget-container ")]',
            '//*[contains(concat(" ", normalize-space(@class), " "), " elementor-widget-theme-post-content ")]',
            '//*[contains(concat(" ", normalize-space(@class), " "), " entry-content ")]',
        ];

        foreach ($queries as $query) {
            $container = $xpath->query($query)->item(0);

            if ($container) {
                return $this->sanitize($container);
            }
        }

        return '';
    }

    /**
     * Rebuilds the body from scratch keeping only the tags the RichEditor
     * knows, so Elementor wrappers, galleries, inline styles and tracking
     * scripts never reach the database.
     */
    public function sanitize(DOMNode $container): string
    {
        $html = '';

        foreach ($container->childNodes as $child) {
            $html .= $this->renderNode($child);
        }

        // Empty paragraphs: WordPress leaves one before the dateline, and the
        // gallery leaves a few behind once its figures are dropped.
        $html = preg_replace('/<p>(?:\s|&nbsp;|\x{00A0}|<br>)*<\/p>/u', '', $html);
        $html = preg_replace('/\s*\n\s*/', "\n", $html);

        return trim($html);
    }

    private function renderNode(DOMNode $node): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            return htmlspecialchars($node->nodeValue, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        }

        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return '';
        }

        /** @var DOMElement $node */
        $tag = strtolower($node->tagName);

        if (in_array($tag, self::DROP_TAGS, true)) {
            return '';
        }

        $inner = '';
        foreach ($node->childNodes as $child) {
            $inner .= $this->renderNode($child);
        }

        if (! in_array($tag, self::KEEP_TAGS, true)) {
            // div, span, section and friends: keep the words, lose the wrapper.
            return $inner;
        }

        if ($tag === 'br') {
            return '<br>';
        }

        if ($tag === 'a') {
            $href = $this->absoluteUrl($node->getAttribute('href'));

            if ($href === null) {
                return $inner;
            }

            return '<a href="'.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'">'.$inner.'</a>';
        }

        $block = in_array($tag, ['p', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote'], true);

        return "<{$tag}>{$inner}</{$tag}>".($block ? "\n" : '');
    }

    private function absoluteUrl(?string $href): ?string
    {
        $href = trim((string) $href);

        if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'javascript:')) {
            return null;
        }

        if (str_starts_with($href, '//')) {
            return 'https:'.$href;
        }

        if (str_starts_with($href, '/')) {
            return 'https://'.self::HOST.$href;
        }

        if (preg_match('#^https?://#i', $href)) {
            return $href;
        }

        if (preg_match('#^(mailto|tel):#i', $href)) {
            return $href;
        }

        return null;
    }

    private function meta(DOMXPath $xpath, string $property): ?string
    {
        $node = $xpath->query('//meta[@property="'.$property.'"]/@content | //meta[@name="'.$property.'"]/@content')->item(0);

        return $node?->nodeValue;
    }

    private function cleanText(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
}
