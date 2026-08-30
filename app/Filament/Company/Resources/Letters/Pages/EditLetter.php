<?php

namespace App\Filament\Company\Resources\Letters\Pages;

use App\Filament\Company\Resources\Letters\LetterResource;
use App\Models\Letter;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLetter extends EditRecord
{
    protected static string $resource = LetterResource::class;

   protected function mutateFormDataBeforeSave(array $data): array
    {
        if (Letter::requiresApproval()) {
            $this->record->submitUpdateRequest($data, 'Letter update request');

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
