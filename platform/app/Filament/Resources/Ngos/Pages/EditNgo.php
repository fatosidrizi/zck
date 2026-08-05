<?php

namespace App\Filament\Resources\Ngos\Pages;

use App\Filament\Resources\Ngos\NgoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNgo extends EditRecord
{
    protected static string $resource = NgoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
