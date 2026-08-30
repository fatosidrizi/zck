<?php

namespace App\Filament\Resources\PublicCalls\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PublicCallForm
{
    public static function configure(Schema $schema): Schema
    {
        $toolbarButtons = ['bold', 'italic', 'underline', 'strike', 'h2', 'h3', 'bulletList', 'orderedList', 'link', 'blockquote', 'redo', 'undo'];

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

                TranslatableTabs::make(function (string $locale, bool $isDefault) use ($toolbarButtons) {
                    $title = TextInput::make("title.{$locale}")
                        ->label('Title ('.strtoupper($locale).')')
                        ->required($isDefault);

                    if ($isDefault) {
                        $title = $title
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)));
                    }

                    return [
                        $title,
                        RichEditor::make("body.{$locale}")
                            ->label('Body ('.strtoupper($locale).')')
                            ->required($isDefault)
                            ->toolbarButtons($toolbarButtons),
                    ];
                }),

                Section::make('Details')
                    ->schema([
                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Auto-generated from English title'),
                        Select::make('type')
                            ->options(['recruitment' => 'Recruitment', 'grant' => 'Grant', 'funding' => 'Funding', 'commission' => 'Commission'])
                            ->default('grant')->required(),
                        FileUpload::make('attachment')->disk('public')->directory('public-calls')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                        DatePicker::make('deadline'),
                        Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->required(),
                        Select::make('author_id')->relationship('author', 'name')->default(fn () => auth()->id())->required(),
                    ])->columns(2),
            ]);
    }
}
