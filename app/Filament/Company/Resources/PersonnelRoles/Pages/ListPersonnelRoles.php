<?php

namespace App\Filament\Company\Resources\PersonnelRoles\Pages;

use App\Filament\Company\Resources\PersonnelRoles\PersonnelRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonnelRoles extends ListRecords
{
    protected static string $resource = PersonnelRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
