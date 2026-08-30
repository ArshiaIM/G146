<?php

namespace App\Filament\Company\Resources\MotorPools\Pages;

use App\Filament\Company\Pages\BaseCreateRecord;
use App\Filament\Company\Resources\MotorPools\MotorPoolResource;
use App\Models\MotorPool;


class CreateMotorPool extends BaseCreateRecord
{
    protected static string $resource = MotorPoolResource::class;

    protected function getModelClass(): string
    {
        return MotorPool::class;
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
