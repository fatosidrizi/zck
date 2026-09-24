<?php

namespace App\Filament\Resources\Communities\Tables;

use App\Filament\Support\TranslatedColumn;
use App\Filament\Support\TranslationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedColumn::make('name')->searchable(),
                TranslationStatus::column('name'),
                TextColumn::make('region')->searchable(),
                ImageColumn::make('image'),
            ])
            ->defaultSort('name')
            ->filters([
                ...TranslationStatus::filters('name'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
