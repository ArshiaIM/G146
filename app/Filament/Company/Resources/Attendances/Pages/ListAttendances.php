<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ ثبت غیبت / فرار'),
        ];
    }
}
