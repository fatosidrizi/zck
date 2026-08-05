<?php

namespace App\Filament\Resources\PublicCalls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PublicCallForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (EN)')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                RichEditor::make('body.en')
                                    ->label('Body (EN)')
                                    ->required()
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3',
                                        'bulletList', 'orderedList',
                                        'link',
                                        'blockquote',
                                        'redo', 'undo',
                                    ]),
                            ]),
                        Tab::make('Shqip')
                            ->schema([
                                TextInput::make('title.sq')->label('Title (SQ)'),
                                RichEditor::make('body.sq')
                                    ->label('Body (SQ)')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3',
                                        'bulletList', 'orderedList',
                                        'link',
                                        'blockquote',
                                        'redo', 'undo',
                                    ]),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from English title'),
                        Select::make('type')
                            ->options(['recruitment' => 'Recruitment', 'grant' => 'Grant', 'funding' => 'Funding', 'commission' => 'Commission'])
                            ->default('grant')->required(),
                        FileUpload::make('attachment')
                            ->disk('public')
                            ->directory('public-calls')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                        DatePicker::make('deadline'),
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published'])
                            ->default('draft')->required(),
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
