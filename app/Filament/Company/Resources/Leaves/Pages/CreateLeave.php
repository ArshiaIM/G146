<?php

namespace App\Filament\Company\Resources\Leaves\Pages;

use App\Filament\Company\Resources\Leaves\LeaveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;
}
