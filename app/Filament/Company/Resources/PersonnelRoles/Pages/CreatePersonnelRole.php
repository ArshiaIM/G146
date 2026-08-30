<?php

namespace App\Filament\Company\Resources\PersonnelRoles\Pages;

use App\Filament\Company\Pages\BaseCreateRecord;
use App\Filament\Company\Resources\PersonnelRoles\PersonnelRoleResource;
use App\Models\PersonnelRole;

class CreatePersonnelRole extends BaseCreateRecord
{
    protected static string $resource = PersonnelRoleResource::class;

    protected function getModelClass(): string
    {
        return PersonnelRole::class;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = auth()->user()?->company_id;
        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
