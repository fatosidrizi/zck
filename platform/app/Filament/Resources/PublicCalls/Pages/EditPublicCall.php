<?php

namespace App\Filament\Resources\PublicCalls\Pages;

use App\Filament\Resources\PublicCalls\PublicCallResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPublicCall extends EditRecord
{
    protected static string $resource = PublicCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
