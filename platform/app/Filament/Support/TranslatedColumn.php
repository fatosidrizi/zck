<?php

namespace App\Filament\Support;

use App\Support\Locales;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

/**
 * A list column for a translatable field that never renders blank.
 *
 * Spatie's accessor reads the panel locale (English) and returns nothing for
 * a record that was imported in Albanian only, so the row looked like it had
 * no title at all. This reads whichever language the record does have and
 * says which one it was.
 */
class TranslatedColumn
{
    public static function make(string $field): TextColumn
    {
        return TextColumn::make($field)
            ->state(function (Model $record) use ($field): string {
                $locale = Locales::resolveFor($record, $field);

                return $locale === null ? '' : (string) $record->getTranslation($field, $locale, false);
            })
            ->description(function (Model $record) use ($field): ?string {
                $locale = Locales::resolveFor($record, $field);

                return $locale === null || $locale === app()->getLocale()
                    ? null
                    : 'Shown in '.Locales::label($locale);
            });
    }
}
