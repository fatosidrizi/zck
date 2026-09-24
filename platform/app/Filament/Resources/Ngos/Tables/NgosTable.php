<?php

namespace App\Filament\Resources\Ngos\Tables;

use App\Filament\Support\TranslatedColumn;
use App\Filament\Support\TranslationStatus;
use App\Models\Ngo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NgosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TranslatedColumn::make('name')
                    ->searchable()
                    ->limit(35),
                TranslationStatus::column('name'),
                TextColumn::make('location')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('reference_number')->label('Ref')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('registration_number')->label('Reg. no.')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_email')->limit(25)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')->label('Published')->boolean(),
                TextColumn::make('submitted_at')->label('Submitted')->dateTime('d M Y')->sortable()->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Filter::make('self_registered')
                    ->label('Self-registered only')
                    ->query(fn (Builder $query) => $query->whereNotNull('submitted_at')),
                ...TranslationStatus::filters('name'),
            ])
            ->recordActions([
                // Reviewing means opening the record and reading it — the row only offers
                // the way in, so nobody approves from a list they have not read.
                EditAction::make()
                    ->label(fn (Ngo $record) => $record->isPending() && $record->wasSelfRegistered()
                        ? __('Review')
                        : __('Edit'))
                    ->icon(fn (Ngo $record) => $record->isPending() && $record->wasSelfRegistered()
                        ? Heroicon::OutlinedClipboardDocumentCheck
                        : null),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
