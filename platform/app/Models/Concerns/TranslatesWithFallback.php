<?php

namespace App\Models\Concerns;

use App\Support\Locales;

/**
 * Reading a translatable field without leaving a hole in the page.
 *
 * Much of the archive was imported in one language only. Spatie's accessor
 * returns an empty string for every other locale, so those records rendered as
 * blank cards — indistinguishable from broken data, and easy to mistake for
 * something worth deleting. These readers fall back to a language the record
 * does have, and say which one it was so the page can label it.
 */
trait TranslatesWithFallback
{
    public function translated(string $field): string
    {
        $locale = Locales::resolveFor($this, $field);

        return $locale === null ? '' : $this->getTranslation($field, $locale, false);
    }

    /** The locale $field was actually read in, or null when it is empty everywhere. */
    public function translationLocale(string $field): ?string
    {
        return Locales::resolveFor($this, $field);
    }

    /** True when $field had to fall back to another language for the current locale. */
    public function isTranslationFallback(string $field): bool
    {
        $locale = Locales::resolveFor($this, $field);

        return $locale !== null && $locale !== app()->getLocale();
    }

    /**
     * Only records where $field has content in at least one language.
     *
     * A record that is empty in every locale has nothing to fall back to, so
     * it would render as a card with no title. Public lists exclude it rather
     * than show a blank row.
     */
    public function scopeWithTranslation($query, string $field)
    {
        return $query->where(function ($query) use ($field) {
            foreach (Locales::all() as $locale) {
                $query->orWhere(function ($query) use ($field, $locale) {
                    $query->whereNotNull("{$field}->{$locale}")
                        ->where("{$field}->{$locale}", '!=', '');
                });
            }
        });
    }
}
