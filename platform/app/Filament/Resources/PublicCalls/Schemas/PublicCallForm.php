<?php

namespace App\Filament\Resources\PublicCalls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PublicCallForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
            'recruitment' => 'Recruitment',
            'grant' => 'Grant',
            'funding' => 'Funding',
            'commission' => 'Commission',
        ])
                    ->default('grant')
                    ->required(),
                TextInput::make('attachment'),
                DatePicker::make('deadline'),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published'])
                    ->default('draft')
                    ->required(),
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),
            ]);
    }
}
