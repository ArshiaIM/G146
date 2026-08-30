<?php

namespace App\Filament\Company\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

abstract class BaseEditRecord extends EditRecord
{
    abstract protected function getModelClass(): string;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $modelClass = $this->getModelClass();

        if ($modelClass::requiresApproval()) {
            $this->record->submitUpdateRequest($data, 'Update request');

            Notification::make()
                ->title('Update request submitted for approval')
                ->warning()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
            throw new Halt();
        }

        return $data;
    }
}
