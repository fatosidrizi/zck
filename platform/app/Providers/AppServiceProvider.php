<?php

namespace App\Providers;

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
    }
}
