<?php

namespace App\Filament\Resources\Ngos\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Decision history';

    public function table(Table $table): Table
    {
        return $table
            ->description('Every decision on this application, oldest at the bottom. Cannot be edited.')
            ->columns([
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('event')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'submitted' => 'Submitted',
                        'approved' => 'Approved & published',
                        'rejected' => 'Rejected — case closed',
                        'reopened' => 'Reopened for review',
                        'unpublished' => 'Unpublished',
                        'republished' => 'Published again',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved', 'republished' => 'success',
                        'rejected' => 'danger',
                        'reopened' => 'warning',
                        'submitted' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('old_status')
                    ->label('From')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('new_status')
                    ->label('To')
                    ->badge(),
                TextColumn::make('changedByUser.name')
                    ->label('By')
                    ->placeholder('The applicant'),
                TextColumn::make('note')
                    ->label('Reason / justification')
                    ->wrap()
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            // A handful of rows never needs a pager or a per-page selector.
            ->paginated(fn () => $this->getRelationship()->count() > 25);
    }
}
