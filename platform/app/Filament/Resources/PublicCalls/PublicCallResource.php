<?php

namespace App\Filament\Resources\PublicCalls;

use App\Filament\Resources\PublicCalls\Pages\CreatePublicCall;
use App\Filament\Resources\PublicCalls\Pages\EditPublicCall;
use App\Filament\Resources\PublicCalls\Pages\ListPublicCalls;
use App\Filament\Resources\PublicCalls\Schemas\PublicCallForm;
use App\Filament\Resources\PublicCalls\Tables\PublicCallsTable;
use App\Models\PublicCall;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublicCallResource extends Resource
{
    protected static ?string $model = PublicCall::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PublicCallForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicCallsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublicCalls::route('/'),
            'create' => CreatePublicCall::route('/create'),
            'edit' => EditPublicCall::route('/{record}/edit'),
        ];
    }
}
