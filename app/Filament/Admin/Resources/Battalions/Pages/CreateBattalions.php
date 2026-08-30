<?php
namespace App\Filament\Admin\Resources\Battalions\Pages;
use App\Filament\Admin\Resources\Battalions\BattalionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
class CreateBattalions extends CreateRecord
{
    protected static string $resource = BattalionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
