<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifies a Cloudflare Turnstile token with the siteverify endpoint.
 *
 * The check fails closed: if Cloudflare cannot be reached, the submission is
 * rejected rather than letting bots through while the widget is down.
 */
class Turnstile implements ValidationRule
{
    public const FIELD = 'cf-turnstile-response';

    public const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /** Turnstile is active only when both keys are configured. */
    public static function enabled(): bool
    {
        return filled(config('services.turnstile.site_key'))
            && filled(config('services.turnstile.secret_key'));
    }

    /**
     * Validation rules for the widget's hidden field, or nothing when disabled,
     * so controllers can spread this into their validate() call unconditionally.
     */
    public static function rules(): array
    {
        return static::enabled()
            ? [static::FIELD => ['required', 'string', new static]]
            : [];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(static::VERIFY_URL, [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            $ok = $response->successful() && $response->json('success') === true;

            if (! $ok) {
                Log::info('Turnstile verification rejected', [
                    'codes' => $response->json('error-codes'),
                    'ip' => request()->ip(),
                ]);
            }
        } catch (ConnectionException $e) {
            Log::warning('Turnstile verification unreachable', ['error' => $e->getMessage()]);
            $ok = false;
        }

        if (! $ok) {
            $fail('The security check failed. Please try again.');
        }
    }
}
