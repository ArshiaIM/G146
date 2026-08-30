<?php

namespace App\Filament\Company\Resources\Armories\Pages;

use App\Filament\Company\Resources\Armories\ArmoryResource;
use App\Models\Armory;
use App\Filament\Company\Pages\BaseCreateRecord;

class CreateArmory extends BaseCreateRecord
{
    protected static string $resource = ArmoryResource::class;

    protected function getModelClass(): string
    {
        return Armory::class;
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

