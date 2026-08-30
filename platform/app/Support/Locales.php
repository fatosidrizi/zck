<?php

namespace App\Support;

use App\Http\Middleware\SetLocale;
use Illuminate\Database\Eloquent\Model;

/**
 * One place that knows about the site's languages.
 *
 * Before this, ten separate spots hardcoded ['en', 'sq', 'sr']. When the site
 * gained Romani, Bosnian and Turkish, every one of them silently kept
 * reporting on three languages — so an editor could be told "all translations
 * complete" while half the locales were empty. Anything that counts, labels or
 * checks a language goes through here.
 */
class Locales
{
    /** Endonyms: a language is named in its own language. */
    public const LABELS = [
        'en' => 'English',
        'sq' => 'Shqip',
        'sr' => 'Srpski',
        'ro' => 'Romani chib',
        'bs' => 'Bosanski',
        'tr' => 'Türkçe',
    ];

    /** @return array<int, string> */
    public static function all(): array
    {
        return SetLocale::SUPPORTED_LOCALES;
    }

    public static function default(): string
    {
        return config('app.locale', 'en');
    }

    public static function label(string $locale): string
    {
        return self::LABELS[$locale] ?? strtoupper($locale);
    }

    /** Every locale except the default one, which is always required. */
    public static function secondary(): array
    {
        return array_values(array_diff(self::all(), [self::default()]));
    }

    public static function hasTranslation(?Model $record, string $field, string $locale): bool
    {
        return $record !== null && filled($record->getTranslation($field, $locale, false));
    }

    /**
     * Locale codes with no value for $field.
     *
     * @return array<int, string>
     */
    public static function missing(?Model $record, string $field): array
    {
        if ($record === null) {
            return [];
        }

        return array_values(array_filter(
            self::all(),
            fn (string $locale) => ! self::hasTranslation($record, $field, $locale)
        ));
    }
}
