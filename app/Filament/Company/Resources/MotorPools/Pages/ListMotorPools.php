<?php

namespace App\Filament\Company\Resources\MotorPools\Pages;

use App\Filament\Company\Resources\MotorPools\MotorPoolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMotorPools extends ListRecords
{
    protected static string $resource = MotorPoolResource::class;
    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
