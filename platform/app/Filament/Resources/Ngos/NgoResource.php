<?php

namespace App\Filament\Resources\Ngos;

use App\Filament\Resources\Ngos\Pages\CreateNgo;
use App\Filament\Resources\Ngos\Pages\EditNgo;
use App\Filament\Resources\Ngos\Pages\ListNgos;
use App\Filament\Resources\Ngos\RelationManagers\StatusHistoriesRelationManager;
use App\Filament\Resources\Ngos\Schemas\NgoForm;
use App\Filament\Resources\Ngos\Tables\NgosTable;
use App\Models\Ngo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NgoResource extends Resource
{
    protected static ?string $model = Ngo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NgoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NgosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StatusHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNgos::route('/'),
            'create' => CreateNgo::route('/create'),
            'edit' => EditNgo::route('/{record}/edit'),
        ];
    }
}
