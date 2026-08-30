<?php

namespace App\Filament\Resources\DiscriminationReports\Pages;

use App\Filament\Resources\DiscriminationReports\DiscriminationReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiscriminationReport extends EditRecord
{
    protected static string $resource = DiscriminationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->color('gray')
                ->outlined(),
        ];
    }
}
