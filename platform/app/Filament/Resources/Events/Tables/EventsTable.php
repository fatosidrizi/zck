<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('translations')
                    ->label('Lang')
                    ->badge()
                    ->state(function ($record): array {
                        $result = [];
                        foreach (['en', 'sq'] as $locale) {
                            $result[] = $record->getTranslation('title', $locale, false) ? strtoupper($locale) : strtoupper($locale) . '!';
                        }
                        return $result;
                    })
                    ->color(fn (string $state): string => str_contains($state, '!') ? 'danger' : 'success'),
                TextColumn::make('event_date')->date('M d, Y')->sortable(),
                TextColumn::make('event_time')->time('H:i'),
                TextColumn::make('location')->searchable()->limit(25),
                TextColumn::make('community.name')->label('Community'),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                Filter::make('missing_translation')
                    ->label('Missing SQ translation')
                    ->query(fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('title->sq')->orWhere('title->sq', ''))),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
