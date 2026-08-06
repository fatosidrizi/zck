<?php

namespace App\Filament\Widgets;

use App\Models\DiscriminationReport;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestReports extends TableWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Discrimination Reports';

    public function table(Table $table): Table
    {
        return $table
            ->query(DiscriminationReport::query()->orderByDesc('created_at')->limit(5))
            ->columns([
                TextColumn::make('tracking_code')->copyable(),
                TextColumn::make('reporter_name'),
                TextColumn::make('type')->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'in_review' => 'info',
                        'resolved' => 'success',
                        'dismissed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Submitted')->dateTime('M d, Y H:i')->sortable(),
            ])
            ->paginated(false);
    }
}
