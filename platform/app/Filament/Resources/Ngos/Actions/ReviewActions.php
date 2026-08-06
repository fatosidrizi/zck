<?php

namespace App\Filament\Resources\Ngos\Actions;

use App\Models\Ngo;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

/**
 * Approving is a decision, not a form field. These actions are the only way status and
 * visibility change, so the two can never drift apart and every change lands in history.
 */
class ReviewActions
{
    public static function approve(): Action
    {
        return Action::make('approve')
            ->label(__('Approve & publish'))
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (?Ngo $record) => $record
                && ! $record->isClosed()
                && ! ($record->isApproved() && $record->is_active))
            ->requiresConfirmation()
            ->modalHeading(fn (Ngo $record) => __('Approve :name?', ['name' => $record->name]))
            ->modalDescription(fn (Ngo $record) => static::approvalSummary($record))
            ->modalSubmitActionLabel(__('Approve & publish'))
            // Publishing what is on screen, not what was on screen an edit ago: commit any
            // unsaved profile changes first, and let validation stop us if they are invalid.
            ->before(function ($livewire) {
                if (method_exists($livewire, 'save')) {
                    $livewire->save(shouldRedirect: false, shouldSendSavedNotification: false);
                }
            })
            ->action(function (Ngo $record) {
                $record->approve();

                Notification::make()
                    ->success()
                    ->title(__('Approved'))
                    ->body(__(':name is now live in the public directory.', ['name' => $record->name]))
                    ->send();
            });
    }

    public static function reject(): Action
    {
        return Action::make('reject')
            ->label(__('Reject & close'))
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            // Outlined so the page is not competing red and green shouting at the reviewer.
            ->outlined()
            ->visible(fn (?Ngo $record) => $record && ! $record->isClosed())
            ->schema([
                Textarea::make('reason')
                    ->label(__('Reason for rejection'))
                    ->helperText(__('Kept permanently in the decision history. Internal only — it is not sent to the organization.'))
                    ->rows(4)
                    ->required(),
            ])
            ->modalHeading(fn (Ngo $record) => __('Reject :name?', ['name' => $record->name]))
            ->modalDescription(__('This closes the case. The record becomes read-only and can only be edited again after an explicit reopen.'))
            ->modalSubmitActionLabel(__('Reject & close'))
            ->action(function (Ngo $record, array $data) {
                $record->reject($data['reason']);

                Notification::make()
                    ->warning()
                    ->title(__('Application rejected'))
                    ->body(__('The case is closed.'))
                    ->send();
            });
    }

    /**
     * The only way out of a closed case, and it costs a written justification.
     */
    public static function reopen(): Action
    {
        return Action::make('reopen')
            ->label(__('Reopen case'))
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->color('warning')
            ->visible(fn (?Ngo $record) => $record?->canBeReopenedBy(auth()->user()) ?? false)
            ->schema([
                Textarea::make('justification')
                    ->label(__('Why is this closed case being reopened?'))
                    ->helperText(__('Kept permanently in the decision history.'))
                    ->rows(4)
                    ->required(),
            ])
            ->modalHeading(fn (Ngo $record) => __('Reopen :name?', ['name' => $record->name]))
            ->modalDescription(__('The application goes back to Pending review and must be assessed again. It is not restored to its previous decision.'))
            ->modalSubmitActionLabel(__('Reopen for review'))
            ->action(function (Ngo $record, array $data) {
                $record->reopen($data['justification']);

                Notification::make()
                    ->success()
                    ->title(__('Case reopened'))
                    ->body(__('The application is back in the pending queue.'))
                    ->send();
            });
    }

    public static function unpublish(): Action
    {
        return Action::make('unpublish')
            ->label(__('Unpublish'))
            ->icon(Heroicon::OutlinedEyeSlash)
            ->color('gray')
            ->visible(fn (?Ngo $record) => $record && $record->isApproved() && $record->is_active)
            ->requiresConfirmation()
            ->modalHeading(fn (Ngo $record) => __('Hide :name from the directory?', ['name' => $record->name]))
            ->modalDescription(__('The application stays approved. You can put it back at any time.'))
            ->action(function (Ngo $record) {
                $record->unpublish();

                Notification::make()
                    ->success()
                    ->title(__('Removed from the public directory'))
                    ->send();
            });
    }

    public static function republish(): Action
    {
        return Action::make('republish')
            ->label(__('Publish again'))
            ->icon(Heroicon::OutlinedEye)
            ->color('success')
            ->visible(fn (?Ngo $record) => $record && $record->isApproved() && ! $record->is_active)
            ->requiresConfirmation()
            ->modalHeading(fn (Ngo $record) => __('Put :name back in the directory?', ['name' => $record->name]))
            ->action(function (Ngo $record) {
                $record->republish();

                Notification::make()
                    ->success()
                    ->title(__('Published'))
                    ->send();
            });
    }

    /**
     * Tells the reviewer exactly what will go live, and what is still thin, before
     * they commit — advisory, never blocking.
     */
    protected static function approvalSummary(Ngo $record): string
    {
        $missing = $record->missingForPublication();

        $summary = __('This publishes the organization in the public NGO directory straight away.');

        if ($missing !== []) {
            $summary .= ' '.__('Its profile is still missing: :items. You can approve now and fill these in afterwards.', [
                'items' => mb_strtolower(implode(', ', $missing)),
            ]);
        }

        return $summary;
    }
}
