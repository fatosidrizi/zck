<?php

namespace App\Filament\Resources\PublicCalls\Pages;

use App\Filament\Resources\PublicCalls\PublicCallResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPublicCalls extends ListRecords
{
    protected static string $resource = PublicCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
