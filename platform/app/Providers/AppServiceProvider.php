<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Auto-inject locale into all route() calls
        URL::defaults(['locale' => app()->getLocale()]);

        // Update defaults whenever locale changes
        $this->app->resolving('url', function ($url) {
            //
        });

        $this->registerRateLimiters();
        $this->configureOutboundHttp();
    }

    /**
     * Unnamed `throttle:x,y` middleware shares a single per-IP bucket across every
     * route that uses it, so walking through the registration wizard would burn the
     * budget for the final submit. Each public form gets its own named limiter.
     */
    protected function registerRateLimiters(): void
    {
        $perIp = fn (int $attempts) => fn (Request $request) => Limit::perMinute($attempts)->by($request->ip());

        RateLimiter::for('registration-steps', $perIp(60));
        RateLimiter::for('registration-submit', $perIp(5));
    }

    /**
     * Hosts whose PHP ships without a CA bundle (Windows, chrooted PHP-FPM) fail
     * every outbound HTTPS call with cURL error 60. HTTP_CA_BUNDLE lets the
     * deployment point at a PEM bundle without editing php.ini. Resolved lazily
     * so a missing file only bites the request that needs it, with Guzzle's
     * clear "SSL CA bundle not found" message rather than a silent fallback.
     */
    protected function configureOutboundHttp(): void
    {
        Http::globalOptions(function (): array {
            $bundle = config('http.ca_bundle');

            if (blank($bundle)) {
                return [];
            }

            $isAbsolute = preg_match('~^(?:[a-zA-Z]:[\\\\/]|[\\\\/])~', $bundle) === 1;

            return ['verify' => $isAbsolute ? $bundle : base_path($bundle)];
        });
    }
}
