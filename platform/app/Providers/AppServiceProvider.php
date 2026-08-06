<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
}
