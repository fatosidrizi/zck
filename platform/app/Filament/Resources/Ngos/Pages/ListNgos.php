<?php

namespace App\Filament\Resources\Ngos\Pages;

use App\Filament\Resources\Ngos\NgoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNgos extends ListRecords
{
    protected static string $resource = NgoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
