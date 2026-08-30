<?php

namespace App\Filament\Support;

use App\Http\Middleware\SetLocale;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Builds one editor tab per supported locale.
 *
 * The locale list lives in SetLocale::SUPPORTED_LOCALES, so adding a language
 * there gives every admin form its tab — the same rule the public language
 * switcher follows. Previously each form hardcoded EN and SQ, which is why
 * Serbian content could never be entered even though the site served /sr.
 */
class TranslatableTabs
{
    /** Endonyms, so a tab is labelled in the language it holds. */
    public const LOCALE_LABELS = [
        'en' => 'English',
        'sq' => 'Shqip',
        'sr' => 'Srpski',
        'ro' => 'Romani chib',
        'bs' => 'Bosanski',
        'tr' => 'Türkçe',
    ];

    /**
     * @param  callable(string, bool): array  $fields  Receives the locale and
     *                                                 whether it is the default
     *                                                 (required) one.
     */
    public static function make(callable $fields): Tabs
    {
        $default = config('app.locale', 'en');

        return Tabs::make('Translations')
            ->tabs(
                collect(SetLocale::SUPPORTED_LOCALES)
                    ->map(fn (string $locale) => Tab::make(self::label($locale))
                        ->schema($fields($locale, $locale === $default)))
                    ->all()
            )
            ->columnSpanFull();
    }

    public static function label(string $locale): string
    {
        return self::LOCALE_LABELS[$locale] ?? strtoupper($locale);
    }
}
