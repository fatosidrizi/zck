<?php

namespace App\Filament\Support;

use App\Support\Locales;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Translation completeness for resource list tables.
 *
 * Listing all six locales per row would spend most of its width restating
 * what is already fine, so only the gaps are shown; a row with nothing
 * missing collapses to a single "Complete". The filters are generated per
 * locale rather than hand-written, which is how the old pair of SQ/SR
 * filters ended up unable to find anything missing in RO, BS or TR.
 */
class TranslationStatus
{
    public static function column(string $field): TextColumn
    {
        return TextColumn::make('translations')
            ->label('Translations')
            ->badge()
            ->state(function ($record) use ($field): array {
                $missing = Locales::missing($record, $field);

                return $missing === []
                    ? ['Complete']
                    : array_map(fn (string $locale) => strtoupper($locale), $missing);
            })
            ->color(fn (string $state): string => $state === 'Complete' ? 'success' : 'danger')
            ->tooltip(fn (?string $state): ?string => $state === null || $state === 'Complete'
                ? null
                : Locales::label(strtolower($state)).' is missing');
    }

    /**
     * One "Missing <language>" filter per non-default locale.
     *
     * @return array<int, Filter>
     */
    public static function filters(string $field): array
    {
        return array_map(
            fn (string $locale) => Filter::make("missing_{$locale}")
                ->label('Missing '.Locales::label($locale))
                ->query(fn (Builder $query) => $query->where(
                    fn (Builder $q) => $q
                        ->whereNull("{$field}->{$locale}")
                        ->orWhere("{$field}->{$locale}", '')
                )),
            Locales::secondary()
        );
    }
}
