<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    public function mount(): void
    {
        // redirect به لیست چون فرم ثبت توی ListAttendances هست
        $this->redirect($this->getResource()::getUrl('index'));
    }
}
