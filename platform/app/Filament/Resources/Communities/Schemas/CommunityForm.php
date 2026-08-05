<?php

namespace App\Filament\Resources\Communities\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name.en')->label('Name (EN)')->required(),
                                Textarea::make('description.en')->label('Description (EN)')->rows(5),
                            ]),
                        Tab::make('Shqip')
                            ->schema([
                                TextInput::make('name.sq')->label('Name (SQ)'),
                                Textarea::make('description.sq')->label('Description (SQ)')->rows(5),
                            ]),
                    ])->columnSpanFull(),
                TextInput::make('slug')->required(),
                FileUpload::make('image')->image()->disk('public')->directory('communities'),
                TextInput::make('population'),
                TextInput::make('region'),
            ]);
    }
}
