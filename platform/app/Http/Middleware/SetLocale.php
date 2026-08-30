<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['en', 'sq', 'sr', 'ro', 'bs', 'tr'];

    /**
     * URL prefix => BCP 47 language tag, where the two differ.
     *
     * The 'ro' prefix is inherited from the old WordPress site, whose Polylang
     * setup borrowed the Romanian locale to hold Romani (rromani chib) because
     * Polylang offers no Romani one. We keep the prefix so existing links and
     * search rankings survive the migration, but the markup must declare the
     * real language: tagging Romani as Romanian makes screen readers apply
     * Romanian pronunciation and search engines index it under the wrong
     * language.
     */
    public const LANGUAGE_TAGS = [
        'ro' => 'rom',
    ];

    /** The BCP 47 tag to publish for a locale, for lang="" and hreflang="". */
    public static function languageTag(string $locale): string
    {
        return self::LANGUAGE_TAGS[$locale] ?? $locale;
    }

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
