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
}
