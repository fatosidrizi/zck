<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
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
                TranslatableTabs::make(fn (string $locale, bool $isDefault) => [
                    TextInput::make("title.{$locale}")
                        ->label('Title')
                        ->required($isDefault),
                    Textarea::make("description.{$locale}")
                        ->label('Description')
                        ->rows(5),
                ], 'title'),

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')->required(),
                        DatePicker::make('event_date')->required(),
                        TimePicker::make('event_time'),
                        TextInput::make('location'),
                        FileUpload::make('image')->image()->disk('public')->directory('events'),
                        Select::make('community_id')->relationship('community', 'name'),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
