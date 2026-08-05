<?php

namespace App\Filament\Resources\Ngos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NgoForm
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
                FileUpload::make('logo')->image()->disk('public')->directory('ngos'),
                TextInput::make('contact_email')->email(),
                TextInput::make('contact_phone')->tel(),
                TextInput::make('website')->url(),
                TextInput::make('location'),
                TextInput::make('category'),
                Toggle::make('is_active')->required(),
            ]);
    }
}
