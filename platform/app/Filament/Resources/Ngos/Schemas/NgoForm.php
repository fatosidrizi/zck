<?php

namespace App\Filament\Resources\Ngos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class NgoForm
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
                        foreach (['en' => 'English', 'sq' => 'Shqip'] as $code => $label) {
                            if (!$record->getTranslation('name', $code, false)) $missing[] = $label;
                        }
                        if (empty($missing)) return new HtmlString('<div style="padding:8px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;color:#166534;font-size:13px;">All translations complete</div>');
                        return new HtmlString('<div style="padding:8px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;color:#991b1b;font-size:13px;">Missing translations: <strong>' . implode(', ', $missing) . '</strong></div>');
                    })
                    ->columnSpanFull()
                    ->hiddenOn('create'),

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

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')->required(),
                        FileUpload::make('logo')->image()->disk('public')->directory('ngos'),
                        TextInput::make('contact_email')->email(),
                        TextInput::make('contact_phone')->tel(),
                        TextInput::make('website')->url(),
                        TextInput::make('location'),
                        TextInput::make('category'),
                        Toggle::make('is_active')->required(),
                    ])->columns(2),
            ]);
    }
}
