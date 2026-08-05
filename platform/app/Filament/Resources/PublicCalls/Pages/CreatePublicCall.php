<?php

namespace App\Filament\Resources\PublicCalls\Pages;

use App\Filament\Resources\PublicCalls\PublicCallResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePublicCall extends CreateRecord
{
    protected static string $resource = PublicCallResource::class;
}
