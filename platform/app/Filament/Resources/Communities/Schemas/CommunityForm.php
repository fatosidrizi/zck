<?php

namespace App\Filament\Resources\Communities\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make(fn (string $locale, bool $isDefault) => [
                    TextInput::make("name.{$locale}")
                        ->label('Name')
                        ->required($isDefault),
                    Textarea::make("description.{$locale}")
                        ->label('Description')
                        ->rows(5),
                ], 'name'),

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')->required(),
                        FileUpload::make('image')->image()->disk('public')->directory('communities'),
                        TextInput::make('region'),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
