<?php

namespace App\Filament\Company\Resources\Armories\Pages;

use App\Filament\Company\Resources\Armories\ArmoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArmories extends ListRecords
{
    protected static string $resource = ArmoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ افزودن سلاح'),
        ];
    }
}
