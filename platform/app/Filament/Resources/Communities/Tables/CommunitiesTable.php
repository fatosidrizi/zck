<?php

namespace App\Filament\Resources\Communities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('translations')
                    ->label('Lang')
                    ->badge()
                    ->state(function ($record): array {
                        $result = [];
                        foreach (['en', 'sq', 'sr'] as $locale) {
                            $result[] = $record->getTranslation('name', $locale, false) ? strtoupper($locale) : strtoupper($locale) . '!';
                        }
                        return $result;
                    })
                    ->color(fn (string $state): string => str_contains($state, '!') ? 'danger' : 'success'),
                TextColumn::make('region')->searchable(),
                TextColumn::make('population'),
                ImageColumn::make('image'),
            ])
            ->defaultSort('name')
            ->filters([
                Filter::make('missing_translation')
                    ->label('Missing SQ translation')
                    ->query(fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('name->sq')->orWhere('name->sq', ''))),
                Filter::make('missing_translation_sr')
                    ->label('Missing SR translation')
                    ->query(fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('name->sr')->orWhere('name->sr', ''))),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
