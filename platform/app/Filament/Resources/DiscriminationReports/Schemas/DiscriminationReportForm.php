<?php

namespace App\Filament\Resources\DiscriminationReports\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DiscriminationReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Report Details')
                    ->schema([
                        TextInput::make('tracking_code')
                            ->disabled()
                            ->dehydrated(true)
                            ->helperText('Auto-generated on creation'),
                        Select::make('status')
                            ->options([
                                'new' => 'New',
                                'in_review' => 'In Review',
                                'resolved' => 'Resolved',
                                'dismissed' => 'Dismissed',
                            ])
                            ->default('new')
                            ->required(),
                        Select::make('assigned_to')
                            ->label('Assign to Staff')
                            ->options(fn () => User::reportHandlers()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Select::make('type')
                            ->options(\App\Http\Controllers\ReportController::types())
                            ->required(),
                    ])->columns(2),

                Section::make('Reporter Information')
                    ->schema([
                        TextInput::make('reporter_name')->required(),
                        TextInput::make('reporter_email')->email(),
                        TextInput::make('reporter_phone')->tel(),
                        TextInput::make('location'),
                        DatePicker::make('incident_date'),
                    ])->columns(2),

                Section::make('Description & Evidence')
                    ->schema([
                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        FileUpload::make('evidence_file')
                            ->disk('public')
                            ->directory('reports/evidence')
                            ->columnSpanFull(),
                    ]),

                Section::make('Admin Notes')
                    ->schema([
                        Textarea::make('admin_notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
