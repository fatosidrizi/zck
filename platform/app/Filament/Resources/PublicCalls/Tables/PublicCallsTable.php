<?php

namespace App\Filament\Resources\PublicCalls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PublicCallsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
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
                TextColumn::make('type')->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                TextColumn::make('deadline')->date('M d, Y')->sortable(),
                TextColumn::make('author.name')->label('Author'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options(['recruitment' => 'Recruitment', 'grant' => 'Grant', 'funding' => 'Funding', 'commission' => 'Commission']),
                SelectFilter::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published']),
                Filter::make('missing_translation')
                    ->label('Missing SQ translation')
                    ->query(fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('title->sq')->orWhere('title->sq', ''))),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
