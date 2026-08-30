<?php
namespace App\Filament\Admin\Resources\Battalions\Pages;
use App\Filament\Admin\Resources\Battalions\BattalionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class ListBattalions extends ListRecords
{
    protected static string $resource = BattalionResource::class;
    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('+ گردان جدید')];
    }
}



