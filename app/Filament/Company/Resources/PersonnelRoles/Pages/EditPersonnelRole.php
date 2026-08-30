<?php

namespace App\Filament\Company\Resources\PersonnelRoles\Pages;

use App\Filament\Company\Pages\BaseEditRecord;
use App\Filament\Company\Resources\PersonnelRoles\PersonnelRoleResource;
use App\Models\PersonnelRole;
use Filament\Actions\DeleteAction;


class EditPersonnelRole extends BaseEditRecord
{
    protected static string $resource = PersonnelRoleResource::class;

    protected function getModelClass(): string
    {
        return PersonnelRole::class;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
