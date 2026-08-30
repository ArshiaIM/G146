<?php

namespace App\Filament\Company\Resources\ApprovalRequests\Pages;

use App\Filament\Company\Resources\ApprovalRequests\ApprovalRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalRequest extends CreateRecord
{
    protected static string $resource = ApprovalRequestResource::class;
}
