<?php

namespace App\Filament\Support;

use App\Support\Locales;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

/**
 * Builds one editor tab per supported locale, each flagged with whether it
 * actually holds a translation.
 *
 * The flag is why this replaces the old status banner: a banner sits far from
 * the tabs and restates what the tabs should say themselves, so an editor had
 * to click all six to find the empty ones. Marking the tab puts the answer
 * where the decision is made.
 */
class TranslatableTabs
{
    /**
     * @param  callable(string, bool): array  $fields  Receives the locale and whether it is the default (required) one.
     * @param  string|null  $indicatorField  Field whose emptiness marks a locale as untranslated.
     */
    public static function make(callable $fields, ?string $indicatorField = null): Tabs
    {
        $default = Locales::default();

        return Tabs::make('Translations')
            // Open on the language the record actually has: an article imported
            // in Albanian only would otherwise greet the editor with empty
            // English fields and look like the import lost its title.
            ->activeTab(fn (?Model $record): int => self::tabIndexFor($record, $indicatorField))
            ->tabs(
                collect(Locales::all())
                    ->map(function (string $locale) use ($fields, $default, $indicatorField) {
                        $tab = Tab::make(Locales::label($locale))
                            ->schema($fields($locale, $locale === $default));

                        if ($indicatorField === null) {
                            return $tab;
                        }

                        // Null badge renders nothing, so only the gaps draw the eye.
                        return $tab
                            ->badge(fn (?Model $record) => $record && ! Locales::hasTranslation($record, $indicatorField, $locale)
                                ? 'Empty'
                                : null)
                            ->badgeColor('danger');
                    })
                    ->all()
            )
            ->columnSpanFull();
    }

    /** 1-based index of the first tab with content, the default locale's tab when none. */
    private static function tabIndexFor(?Model $record, ?string $field): int
    {
        $locales = Locales::all();
        $locale = $field === null ? null : Locales::resolveFor($record, $field, Locales::default());

        $index = array_search($locale ?? Locales::default(), $locales, true);

        return $index === false ? 1 : $index + 1;
    }
}
