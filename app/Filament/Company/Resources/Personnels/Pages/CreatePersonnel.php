<?php

namespace App\Filament\Company\Resources\Personnels\Pages;

use App\Filament\Company\Resources\Personnels\PersonnelResource;
use App\Models\Personnel;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonnel extends CreateRecord
{
    protected static string $resource = PersonnelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = auth()->user()?->company_id;
        // اگه operator بود، درخواست تایید بفرست
        if (Personnel::requiresApproval()) {
            Personnel::submitCreateRequest($data, 'New personnel registration');

            Notification::make()
                ->title('Request submitted for approval')
                ->warning()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));

            // جلوگیری از ادامه create
            throw new \Filament\Support\Exceptions\Halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
