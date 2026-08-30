<?php

namespace App\Filament\Resources\PublicCalls\Tables;

use App\Filament\Support\TranslationStatus;
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
                TranslationStatus::column('title'),
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
                ...TranslationStatus::filters('title'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
