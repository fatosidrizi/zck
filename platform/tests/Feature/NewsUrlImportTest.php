<?php

namespace Tests\Feature;

use App\Filament\Resources\News\Pages\ListNews;
use App\Models\News;
use App\Models\User;
use App\Services\LegacyNews\NewsUrlImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class NewsUrlImportTest extends TestCase
{
    use RefreshDatabase;

    private const SQ = 'https://kryeministri.rks-gov.net/news/test-article/';

    private const EN = 'https://kryeministri.rks-gov.net/en/news/test-article-en/';

    private const SR = 'https://kryeministri.rks-gov.net/sr/news/test-article-sr/';

    private const IMAGE = 'https://kryeministri.rks-gov.net/wp-content/uploads/2026/08/cover.jpg';

    private const MKK_SQ = 'https://mkk.rks-gov.net/news/nje-hap-i-ri/';

    private const MKK_EN = 'https://mkk.rks-gov.net/en/news/a-new-step/';

    private const MKK_IMAGE = 'https://mkk.rks-gov.net/wp-content/uploads/2026/09/slika-1.png';

    private const MAPL_SQ = 'https://mapl.rks-gov.net/news/grupi-punues/';

    private const MAPL_IMAGE = 'https://mapl.rks-gov.net/wp-content/uploads/2026/09/1-4.jpg';

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function fixture(string $name, string $site = 'kryeministri'): string
    {
        return file_get_contents(base_path("tests/Fixtures/{$site}/{$name}"));
    }

    /** @param  array<string, mixed>  $overrides */
    private function fakeSite(array $overrides = []): void
    {
        Http::fake(array_merge([
            self::SQ => Http::response($this->fixture('sq.html')),
            self::EN => Http::response($this->fixture('en.html')),
            self::SR => Http::response($this->fixture('sr.html')),
            self::IMAGE => Http::response($this->fixture('cover.jpg'), 200, ['Content-Type' => 'image/jpeg']),
            self::MKK_SQ => Http::response($this->fixture('sq.html', 'mkk')),
            self::MKK_EN => Http::response($this->fixture('en.html', 'mkk')),
            self::MKK_IMAGE => Http::response($this->fixture('cover.jpg'), 200, ['Content-Type' => 'image/png']),
            self::MAPL_SQ => Http::response($this->fixture('sq.html', 'mapl')),
            self::MAPL_IMAGE => Http::response($this->fixture('cover.jpg'), 200, ['Content-Type' => 'image/jpeg']),
            '*' => Http::response('not found', 404),
        ], $overrides));
    }

    private function importer(): NewsUrlImporter
    {
        return app(NewsUrlImporter::class);
    }

    public function test_it_imports_all_three_languages_as_a_draft_with_cover_image(): void
    {
        $this->fakeSite();

        $result = $this->importer()->import(self::SQ, 'news', $this->admin);

        $this->assertTrue($result->isCreated(), $result->message ?? '');
        $news = $result->record->fresh();

        $this->assertSame('draft', $news->status);
        $this->assertSame('news', $news->category);
        $this->assertSame(self::SQ, $news->source_url);
        $this->assertSame($this->admin->id, $news->author_id);
        $this->assertSame('2026-08-31 12:52:34', $news->published_at->toDateTimeString());
        $this->assertSame('kryeministri-mori-pjese-ne-takimin-me-zyrat-komunale-per-komunitete', $news->slug);

        $this->assertSame('Kryeministri mori pjesë në takimin me Zyrat Komunale për Komunitete', $news->getTranslation('title', 'sq'));
        $this->assertSame('Prime Minister Attended a Meeting with Municipal Offices for Communities', $news->getTranslation('title', 'en'));
        $this->assertSame('Premijer učestvovao na sastanku sa opštinskim kancelarijama za zajednice', $news->getTranslation('title', 'sr'));

        $body = $news->getTranslation('body', 'sq');
        $this->assertStringContainsString('<p><strong>Prishtinë, 31 gusht 2026</strong></p>', $body);
        $this->assertStringContainsString('<a href="https://kryeministri.rks-gov.net/news/other-article/">Lexo më shumë</a>', $body);
        $this->assertStringContainsString('<br>Rreshti i dytë.', $body);
        $this->assertStringNotContainsString('<img', $body);
        $this->assertStringNotContainsString('<figure', $body);
        $this->assertStringNotContainsString('<div', $body);
        $this->assertStringNotContainsString('<script', $body);
        $this->assertStringNotContainsString('style=', $body);
        $this->assertStringNotContainsString('Lajme të ngjashme', $body, 'content outside the post-content widget leaked in');
        $this->assertDoesNotMatchRegularExpression('/<p>(\s|&nbsp;)*<\/p>/', $body, 'empty paragraphs should be removed');
        $this->assertStringContainsString('Office for Community Issues', $news->getTranslation('body', 'en'));

        $this->assertNotNull($news->image);
        $this->assertStringStartsWith('news/kryeministri-mori-pjese', $news->image);
        $this->assertStringEndsWith('.jpg', $news->image);
        Storage::disk('public')->assertExists($news->image);
    }

    public function test_the_same_link_is_skipped_the_second_time(): void
    {
        $this->fakeSite();

        $first = $this->importer()->import(self::SQ, 'news', $this->admin);
        $second = $this->importer()->import('http://www.kryeministri.rks-gov.net/news/test-article?utm=x', 'news', $this->admin);

        $this->assertTrue($first->isCreated());
        $this->assertTrue($second->isSkipped());
        $this->assertSame($first->record->id, $second->record->id);
        $this->assertSame(1, News::count());
    }

    public function test_a_missing_translation_does_not_block_the_import(): void
    {
        $this->fakeSite([
            self::EN => Http::response('gone', 500),
            self::SR => Http::response('gone', 500),
        ]);

        $result = $this->importer()->import(self::SQ, 'bulletin', $this->admin);

        $this->assertTrue($result->isCreated(), $result->message ?? '');
        $news = $result->record->fresh();
        $this->assertSame('bulletin', $news->category);
        $this->assertSame(['sq'], array_keys($news->getTranslations('title')));
        $this->assertSame(['sq'], array_keys($news->getTranslations('body')));
    }

    public function test_a_failed_cover_download_still_creates_the_record(): void
    {
        $this->fakeSite([self::IMAGE => Http::response('nope', 404)]);

        $result = $this->importer()->import(self::SQ, 'news', $this->admin);

        $this->assertTrue($result->isCreated());
        $this->assertNull($result->record->image);
        $this->assertStringContainsString('cover image', $result->message);
    }

    public function test_it_imports_mkk_articles_without_yoast_metadata(): void
    {
        $this->fakeSite();

        $result = $this->importer()->import('https://www.mkk.rks-gov.net/news/nje-hap-i-ri?fbclid=abc', 'news', $this->admin);

        $this->assertTrue($result->isCreated(), $result->message ?? '');
        $news = $result->record->fresh();

        $this->assertSame(self::MKK_SQ, $news->source_url);
        $this->assertSame('2026-09-18 12:20:00', $news->published_at->toDateTimeString(), 'date comes from the Elementor post-info widget');
        $this->assertSame('nje-hap-i-ri-drejt-kushteve-me-te-mira-te-strehimit', $news->slug);

        $this->assertSame('Një hap i ri drejt kushteve më të mira të strehimit', $news->getTranslation('title', 'sq'));
        $this->assertSame('A new step towards better housing conditions', $news->getTranslation('title', 'en'));
        $this->assertSame(['sq', 'en'], array_keys($news->getTranslations('title')));

        $body = $news->getTranslation('body', 'sq');
        $this->assertStringContainsString('<p><strong>Një hap i ri drejt kushteve më të mira të strehimit.</strong></p>', $body);
        $this->assertStringContainsString('<a href="https://mkk.rks-gov.net/thirrje-publike/">Thirrjet publike</a>', $body, 'root-relative links resolve against the mkk host');
        $this->assertStringNotContainsString('Ministria për Komunitete dhe Kthim', $body, 'footer leaked in');
        $this->assertStringNotContainsString('Share', $body);
        $this->assertStringContainsString('signed the contract', $news->getTranslation('body', 'en'));

        $this->assertNotNull($news->image, 'cover comes from the first carousel slide');
        $this->assertStringEndsWith('.png', $news->image);
        Storage::disk('public')->assertExists($news->image);
        Http::assertSent(fn ($request) => $request->url() === self::MKK_IMAGE);
    }

    public function test_the_mkk_placeholder_image_is_not_imported_as_a_cover(): void
    {
        $this->fakeSite();

        $result = $this->importer()->import(self::MKK_EN, 'news', $this->admin);

        $this->assertTrue($result->isCreated(), $result->message ?? '');
        $this->assertNull($result->record->image);
        $this->assertNull($result->message, 'no "download failed" note when the source simply has no cover');
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'default-image'));
    }

    public function test_it_imports_mapl_articles_that_exist_only_in_albanian(): void
    {
        $this->fakeSite();

        $result = $this->importer()->import(self::MAPL_SQ, 'news', $this->admin);

        $this->assertTrue($result->isCreated(), $result->message ?? '');
        $news = $result->record->fresh();

        $this->assertSame(self::MAPL_SQ, $news->source_url);
        $this->assertSame('Grupi Punues për aktet nënligjore mbajti takimin e radhës', $news->getTranslation('title', 'sq'));
        $this->assertSame(['sq'], array_keys($news->getTranslations('title')));
        $this->assertSame('2026-09-23 12:10:11', $news->published_at->toDateTimeString());

        $body = $news->getTranslation('body', 'sq');
        $this->assertStringContainsString('<p>Në këtë takim kanë marrë pjesë z. Agon Batusha, Zëvendësministër i MAPL-së.</p>', $body);
        $this->assertStringNotContainsString('Grupi Punues për aktet', $body, 'headline leaked into the body');

        $this->assertNotNull($news->image);
        Storage::disk('public')->assertExists($news->image);
        Http::assertSent(fn ($request) => $request->url() === self::MAPL_IMAGE);
    }

    public function test_links_from_other_hosts_are_rejected_without_a_request(): void
    {
        Http::fake();

        $result = $this->importer()->import('https://example.com/news/whatever/', 'news', $this->admin);

        $this->assertTrue($result->isFailed());
        $this->assertStringContainsString('kryeministri.rks-gov.net', $result->message);
        $this->assertStringContainsString('mkk.rks-gov.net', $result->message);
        $this->assertStringContainsString('mapl.rks-gov.net', $result->message);
        Http::assertNothingSent();
        $this->assertSame(0, News::count());
    }

    public function test_a_page_without_a_title_is_reported_as_failed(): void
    {
        $this->fakeSite([self::SQ => Http::response('<html lang="sq"><body><p>nothing here</p></body></html>')]);

        $result = $this->importer()->import(self::SQ, 'news', $this->admin);

        $this->assertTrue($result->isFailed());
        $this->assertSame(0, News::count());
    }

    public function test_an_unreachable_page_is_reported_as_failed(): void
    {
        $this->fakeSite([self::SQ => Http::response('blocked', 403)]);

        $result = $this->importer()->import(self::SQ, 'news', $this->admin);

        $this->assertTrue($result->isFailed());
        $this->assertStringContainsString('403', $result->message);
        $this->assertSame(0, News::count());
    }

    public function test_slugs_are_made_unique(): void
    {
        $this->fakeSite();

        News::create([
            'title' => ['en' => 'Existing'],
            'slug' => 'kryeministri-mori-pjese-ne-takimin-me-zyrat-komunale-per-komunitete',
            'body' => ['en' => '<p>x</p>'],
            'category' => 'news',
            'status' => 'published',
            'author_id' => $this->admin->id,
        ]);

        $result = $this->importer()->import(self::SQ, 'news', $this->admin);

        $this->assertTrue($result->isCreated());
        $this->assertSame('kryeministri-mori-pjese-ne-takimin-me-zyrat-komunale-per-komunitete-2', $result->record->slug);
    }

    public function test_admins_can_import_from_the_news_list(): void
    {
        $this->fakeSite();
        $this->actingAs($this->admin);

        Livewire::test(ListNews::class)
            ->callAction('importFromUrl', [
                'urls' => self::SQ."\n\n".self::SQ."\nhttps://example.com/not-allowed/",
                'category' => 'report',
            ])
            ->assertHasNoActionErrors()
            ->assertNotified();

        $this->assertSame(1, News::count());
        $this->assertSame('report', News::first()->category);
        $this->assertSame('draft', News::first()->status);
    }

    public function test_the_action_requires_links_or_a_csv(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListNews::class)
            ->callAction('importFromUrl', ['urls' => '', 'category' => 'news'])
            ->assertHasActionErrors(['urls']);
    }

    public function test_the_artisan_command_imports_and_reports(): void
    {
        $this->fakeSite();
        User::factory()->create(['role' => 'super_admin']);

        $this->artisan('zck:import-news-url', ['urls' => [self::SQ]])
            ->expectsOutputToContain('[CREATED]')
            ->expectsOutputToContain('Done: 1 created, 0 skipped, 0 failed.')
            ->assertSuccessful();

        $this->assertSame(1, News::count());
    }

    public function test_single_language_news_falls_back_on_the_public_site(): void
    {
        $news = News::create([
            'title' => ['sq' => 'Vetëm në shqip'],
            'slug' => 'vetem-ne-shqip',
            'body' => ['sq' => '<p>Trupi i lajmit.</p>'],
            'category' => 'news',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => $this->admin->id,
        ]);

        $this->get('/en/news/'.$news->slug)
            ->assertOk()
            ->assertSee('Vetëm në shqip')
            ->assertSee('Trupi i lajmit.')
            ->assertSee('Only available in Shqip');

        $this->get('/en/news')
            ->assertOk()
            ->assertSee('Vetëm në shqip')
            ->assertSee('Only available in Shqip');

        $this->get('/en')->assertOk()->assertSee('Vetëm në shqip');
    }
}
