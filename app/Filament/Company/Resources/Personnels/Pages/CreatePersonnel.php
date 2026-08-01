<?php

namespace App\Filament\Company\Resources\Personnels\Pages;

use App\Filament\Company\Resources\Personnels\PersonnelResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonnel extends CreateRecord
{
    protected static string $resource = PersonnelResource::class;
}
