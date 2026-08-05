<?php

namespace App\Filament\Resources\DiscriminationReports;

use App\Filament\Resources\DiscriminationReports\Pages\CreateDiscriminationReport;
use App\Filament\Resources\DiscriminationReports\Pages\EditDiscriminationReport;
use App\Filament\Resources\DiscriminationReports\Pages\ListDiscriminationReports;
use App\Filament\Resources\DiscriminationReports\RelationManagers\StatusHistoriesRelationManager;
use App\Filament\Resources\DiscriminationReports\Schemas\DiscriminationReportForm;
use App\Filament\Resources\DiscriminationReports\Tables\DiscriminationReportsTable;
use App\Models\DiscriminationReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscriminationReportResource extends Resource
{
    protected static ?string $model = DiscriminationReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DiscriminationReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscriminationReportsTable::configure($table);
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
            'index' => ListDiscriminationReports::route('/'),
            'create' => CreateDiscriminationReport::route('/create'),
            'edit' => EditDiscriminationReport::route('/{record}/edit'),
        ];
    }
}
