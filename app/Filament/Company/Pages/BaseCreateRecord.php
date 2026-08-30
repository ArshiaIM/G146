<?php

namespace App\Filament\Company\Pages;

use App\Traits\RequiresApproval;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

abstract class BaseCreateRecord extends CreateRecord
{
    abstract protected function getModelClass(): string;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $modelClass = $this->getModelClass();

        if ($modelClass::requiresApproval()) {
            $modelClass::submitCreateRequest($data, 'Create request');

            Notification::make()
                ->title('Request submitted for approval')
                ->warning()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
            throw new Halt();
        }

        return $data;
    }
}
