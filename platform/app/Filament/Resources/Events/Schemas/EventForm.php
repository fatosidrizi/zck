<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title.en')->label('Title (EN)')->required(),
                                Textarea::make('description.en')->label('Description (EN)')->rows(5),
                            ]),
                        Tab::make('Shqip')
                            ->schema([
                                TextInput::make('title.sq')->label('Title (SQ)'),
                                Textarea::make('description.sq')->label('Description (SQ)')->rows(5),
                            ]),
                    ])->columnSpanFull(),
                TextInput::make('slug')->required(),
                DatePicker::make('event_date')->required(),
                TimePicker::make('event_time'),
                TextInput::make('location'),
                FileUpload::make('image')->image()->disk('public')->directory('events'),
                Select::make('community_id')->relationship('community', 'name'),
            ]);
    }
}
