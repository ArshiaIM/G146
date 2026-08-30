<?php

namespace App\Filament\Company\Resources\ApprovalRequests\Pages;

use App\Filament\Company\Resources\ApprovalRequests\ApprovalRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApprovalRequests extends ListRecords
{
    protected static string $resource = ApprovalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
