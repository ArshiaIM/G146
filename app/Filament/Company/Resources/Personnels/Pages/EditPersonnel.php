<?php

namespace App\Filament\Company\Resources\Personnels\Pages;

use App\Filament\Company\Resources\Personnels\PersonnelResource;
use App\Models\Personnel;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPersonnel extends EditRecord
{
    protected static string $resource = PersonnelResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Personnel::requiresApproval()) {
            $this->record->submitUpdateRequest($data, 'Personnel update request');

            Notification::make()
                ->title('Update request submitted for approval')
                ->warning()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));

            throw new \Filament\Support\Exceptions\Halt();
        }

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
