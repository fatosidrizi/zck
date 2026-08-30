<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('translation_status')
                    ->label('')
                    ->content(function ($record) {
                        if (!$record) return '';
                        $missing = [];
                        foreach (['en' => 'English', 'sq' => 'Shqip', 'sr' => 'Srpski'] as $code => $label) {
                            if (!$record->getTranslation('title', $code, false)) $missing[] = $label;
                        }
                        if (empty($missing)) return new HtmlString('<div style="padding:8px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#166534;font-size:13px;">All translations complete</div>');
                        return new HtmlString('<div style="padding:8px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#991b1b;font-size:13px;">Missing translations: <strong>' . implode(', ', $missing) . '</strong></div>');
                    })
                    ->columnSpanFull()
                    ->hiddenOn('create'),

                TranslatableTabs::make(fn (string $locale, bool $isDefault) => [
                    TextInput::make("title.{$locale}")
                        ->label('Title ('.strtoupper($locale).')')
                        ->required($isDefault),
                    Textarea::make("description.{$locale}")
                        ->label('Description ('.strtoupper($locale).')')
                        ->rows(5),
                ]),

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')->required(),
                        DatePicker::make('event_date')->required(),
                        TimePicker::make('event_time'),
                        TextInput::make('location'),
                        FileUpload::make('image')->image()->disk('public')->directory('events'),
                        Select::make('community_id')->relationship('community', 'name'),
                    ])->columns(2),
            ]);
    }
}
