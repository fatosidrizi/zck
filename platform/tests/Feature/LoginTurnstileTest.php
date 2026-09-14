<?php

namespace Tests\Feature;

use App\Models\User;
use App\Rules\Turnstile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LoginTurnstileTest extends TestCase
{
    use RefreshDatabase;

    protected function enableTurnstile(): void
    {
        config([
            'services.turnstile.site_key' => 'site-key',
            'services.turnstile.secret_key' => 'secret-key',
        ]);
    }

    protected function credentials(array $extra = []): array
    {
        User::factory()->create(['email' => 'user@example.com']);

        return ['email' => 'user@example.com', 'password' => 'password'] + $extra;
    }

    public function test_widget_is_absent_and_login_works_when_keys_are_not_configured(): void
    {
        config(['services.turnstile.site_key' => null, 'services.turnstile.secret_key' => null]);
        Http::fake();

        $this->get('/en/login')
            ->assertOk()
            ->assertDontSee('cf-turnstile');

        $this->post('/en/login', $this->credentials())->assertRedirect();

        $this->assertAuthenticated();
        Http::assertNothingSent();
    }

    public function test_widget_is_rendered_when_keys_are_configured(): void
    {
        $this->enableTurnstile();

        $this->get('/en/login')
            ->assertOk()
            ->assertSee('cf-turnstile')
            ->assertSee('data-sitekey="site-key"', false)
            ->assertSee('challenges.cloudflare.com/turnstile/v0/api.js');
    }

    public function test_login_is_rejected_without_a_token(): void
    {
        $this->enableTurnstile();
        Http::fake();

        $this->from('/en/login')
            ->post('/en/login', $this->credentials())
            ->assertRedirect('/en/login')
            ->assertSessionHasErrors(Turnstile::FIELD);

        $this->assertGuest();
        Http::assertNothingSent();
    }

    public function test_login_is_rejected_when_cloudflare_rejects_the_token(): void
    {
        $this->enableTurnstile();
        Http::fake([Turnstile::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

        $this->from('/en/login')
            ->post('/en/login', $this->credentials([Turnstile::FIELD => 'bad-token']))
            ->assertRedirect('/en/login')
            ->assertSessionHasErrors(Turnstile::FIELD);

        $this->assertGuest();
    }

    public function test_login_is_rejected_when_cloudflare_is_unreachable(): void
    {
        $this->enableTurnstile();
        Http::fake(fn () => throw new ConnectionException('timeout'));

        $this->from('/en/login')
            ->post('/en/login', $this->credentials([Turnstile::FIELD => 'token']))
            ->assertRedirect('/en/login')
            ->assertSessionHasErrors(Turnstile::FIELD);

        $this->assertGuest();
    }

    public function test_login_succeeds_with_a_valid_token(): void
    {
        $this->enableTurnstile();
        Http::fake([Turnstile::VERIFY_URL => Http::response(['success' => true])]);

        $this->post('/en/login', $this->credentials([Turnstile::FIELD => 'good-token']))->assertRedirect();

        $this->assertAuthenticated();
        Http::assertSent(fn ($request) => $request->url() === Turnstile::VERIFY_URL
            && $request['secret'] === 'secret-key'
            && $request['response'] === 'good-token');
    }
}
