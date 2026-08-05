<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['en', 'sq'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if ($locale && in_array($locale, self::SUPPORTED_LOCALES)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.locale', 'en'));
        }

        URL::defaults(['locale' => app()->getLocale()]);

        return $next($request);
    }
}
