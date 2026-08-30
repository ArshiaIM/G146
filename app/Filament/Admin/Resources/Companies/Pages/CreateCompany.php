<?php

namespace App\Filament\Admin\Resources\Companies\Pages;

use App\Filament\Admin\Resources\Companies\CompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
