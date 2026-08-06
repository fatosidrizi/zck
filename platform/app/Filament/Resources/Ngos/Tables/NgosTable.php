<?php

namespace App\Filament\Resources\Ngos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NgosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('translations')
                    ->label('Lang')
                    ->badge()
                    ->state(function ($record): array {
                        $result = [];
                        foreach (['en', 'sq'] as $locale) {
                            $result[] = $record->getTranslation('name', $locale, false) ? strtoupper($locale) : strtoupper($locale) . '!';
                        }
                        return $result;
                    })
                    ->color(fn (string $state): string => str_contains($state, '!') ? 'danger' : 'success'),
                TextColumn::make('location')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('contact_email')->limit(25)->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('missing_translation')
                    ->label('Missing SQ translation')
                    ->query(fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('name->sq')->orWhere('name->sq', ''))),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
