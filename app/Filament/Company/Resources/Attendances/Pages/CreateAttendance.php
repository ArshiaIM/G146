<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // وقتی غیبت ثبت میشه، وضعیت پرسنل رو آپدیت کن
        if (isset($data['personnel_id'])) {
            \App\Models\Personnel::find($data['personnel_id'])
                ?->update(['status' => $data['type']]);
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
