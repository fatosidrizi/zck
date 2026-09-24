<?php

namespace App\Filament\Resources\Events\Tables;

use App\Filament\Support\TranslatedColumn;
use App\Filament\Support\TranslationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedColumn::make('title')
                    ->searchable()
                    ->limit(35),
                TranslationStatus::column('title'),
                TextColumn::make('event_date')->date('M d, Y')->sortable(),
                TextColumn::make('event_time')->time('H:i'),
                TextColumn::make('location')->searchable()->limit(25),
                TextColumn::make('community.name')->label('Community'),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                ...TranslationStatus::filters('title'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
