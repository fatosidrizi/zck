<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),
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
                    ->color(fn (string $state): string => str_contains($state, '!') ? 'danger' : 'success')
                    ->toggleable(),
                TextColumn::make('category')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->options(['news' => 'News', 'bulletin' => 'Bulletin', 'report' => 'Report']),
                SelectFilter::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published']),
                Filter::make('missing_translation')
                    ->label('Missing SQ translation')
                    ->query(fn (Builder $query) => $query->where(function ($q) {
                        $q->whereNull('title->sq')->orWhere('title->sq', '');
                    })),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
