<?php

namespace App\Filament\Company\Resources\Warehouses\Pages;

use App\Filament\Company\Pages\BaseEditRecord;
use App\Filament\Company\Resources\Warehouses\WarehouseResource;
use App\Models\Warehouse;
use Filament\Actions\DeleteAction;


class EditWarehouse extends BaseEditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getModelClass(): string
    {
        return Warehouse::class;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
