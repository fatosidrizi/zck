<?php

namespace App\Filament\Resources\Ngos\Pages;

use App\Filament\Resources\Ngos\Actions\ReviewActions;
use App\Filament\Resources\Ngos\NgoResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNgo extends EditRecord
{
    protected static string $resource = NgoResource::class;

    public function getTitle(): string
    {
        return match (true) {
            $this->record->isClosed() => __('Closed application'),
            $this->record->isPending() && $this->record->wasSelfRegistered() => __('Review application'),
            default => __('Edit NGO'),
        };
    }

    /**
     * The decision belongs at the end of the page, after everything there is to check.
     * Up here it is only a shortcut, so it stays quiet — and Delete is tucked into a menu
     * rather than sitting one pixel away from Approve.
     */
    protected function getHeaderActions(): array
    {
        return [
            ReviewActions::approve()->outlined(),
            ReviewActions::reopen()->outlined(),
            ActionGroup::make([
                ReviewActions::reject(),
                ReviewActions::republish(),
                ReviewActions::unpublish(),
                DeleteAction::make(),
            ]),
        ];
    }

    /**
     * Sits directly under the Decision section, so approving is the last thing you reach
     * after reading the application. Saving is secondary here — the page is for deciding.
     */
    protected function getFormActions(): array
    {
        if ($this->record->isClosed()) {
            return [
                ReviewActions::reopen()->record($this->record),
                $this->getCancelFormAction(),
            ];
        }

        return [
            ReviewActions::approve()->record($this->record),
            ReviewActions::reject()->record($this->record),
            ReviewActions::republish()->record($this->record),
            ReviewActions::unpublish()->record($this->record),
            $this->getSaveFormAction()->label(__('Save changes'))->color('gray')->outlined(),
            $this->getCancelFormAction(),
        ];
    }
}
