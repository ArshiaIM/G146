<?php

namespace App\Filament\Company\Resources\Medicals\Pages;

use App\Filament\Company\Resources\Medicals\MedicalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedicals extends ListRecords
{
    protected static string $resource = MedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
