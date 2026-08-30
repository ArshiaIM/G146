<?php

namespace App\Filament\Company\Resources\Leaves\Pages;

use App\Filament\Company\Resources\Leaves\LeaveResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeave extends EditRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
