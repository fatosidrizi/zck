<?php

namespace Tests\Feature;

use App\Models\PublicCall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCallListingTest extends TestCase
{
    use RefreshDatabase;

    private function publishedCall(array $title, string $slug): PublicCall
    {
        $call = new PublicCall([
            'slug' => $slug,
            'type' => 'grant',
            'status' => 'published',
            'author_id' => User::factory()->create(['role' => 'admin'])->id,
        ]);
        $call->setTranslations('title', $title);
        $call->setTranslations('body', ['en' => 'Body']);
        $call->save();

        return $call;
    }

    public function test_a_call_with_no_title_in_any_language_is_hidden_from_public_lists(): void
    {
        $this->publishedCall(['en' => 'Visible call'], 'visible-call');
        $this->publishedCall(['en' => '', 'sq' => ''], 'blank-call');

        $this->get('/en')
            ->assertOk()
            ->assertSee('Visible call')
            ->assertDontSee('blank-call');

        $this->get('/en/public-calls')
            ->assertOk()
            ->assertSee('Visible call')
            ->assertDontSee('blank-call');
    }

    public function test_the_homepage_falls_back_to_a_language_the_call_has(): void
    {
        $this->publishedCall(['sq' => 'Thirrje vetëm në shqip'], 'sq-only-call');

        $this->get('/en')
            ->assertOk()
            ->assertSee('Thirrje vetëm në shqip')
            ->assertSee('sq-only-call');
    }
}
