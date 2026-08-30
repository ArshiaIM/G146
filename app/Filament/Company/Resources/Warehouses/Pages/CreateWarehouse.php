<?php

namespace App\Filament\Company\Resources\Warehouses\Pages;

use App\Filament\Company\Pages\BaseCreateRecord;
use App\Filament\Company\Resources\Warehouses\WarehouseResource;
use App\Models\Warehouse;
// use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends BaseCreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getModelClass(): string
    {
        return Warehouse::class;
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
