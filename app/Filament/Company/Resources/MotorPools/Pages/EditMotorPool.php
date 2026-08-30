<?php

namespace App\Filament\Company\Resources\MotorPools\Pages;

use App\Filament\Company\Pages\BaseEditRecord;
use App\Filament\Company\Resources\MotorPools\MotorPoolResource;
use App\Models\MotorPool;
use Filament\Actions\DeleteAction;

class EditMotorPool extends BaseEditRecord
{
    protected static string $resource = MotorPoolResource::class;

    protected function getModelClass(): string
    {
        return MotorPool::class;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
