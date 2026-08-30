<?php

namespace App\Filament\Company\Resources\Armories\Pages;

use App\Filament\Company\Resources\Armories\ArmoryResource;
use Filament\Actions\DeleteAction;
use App\Filament\Company\Pages\BaseEditRecord;

class EditArmory extends BaseEditRecord
{
    protected static string $resource = ArmoryResource::class;

    protected function getModelClass(): string
    {
        return Armory::class;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
