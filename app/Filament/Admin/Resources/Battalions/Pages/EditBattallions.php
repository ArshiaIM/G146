<?php

namespace App\Filament\Admin\Resources\Battalions\Pages;

use App\Filament\Admin\Resources\Battalions\BattalionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditBattallions extends EditRecord
{
    protected static string $resource = BattalionResource::class;
    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\DeleteAction::make()];
    }
    protected function afterSave(): void
{
    $battalion = $this->record;

    if (!$battalion->commander_id) return;

    // پیدا کردن گروهان ارکان این گردان
    $arkan = \App\Models\Company::where('battalion_id', $battalion->id)
        ->where('name', 'like', '%ارکان%')
        ->first();

    if (!$arkan) return;

    // آپدیت company_id فرمانده
    \App\Models\Personnel::where('id', $battalion->commander_id)
        ->update(['company_id' => $arkan->id]);

    // آپدیت یا ساخت یوزر برای فرمانده
    $personnel = \App\Models\Personnel::find($battalion->commander_id);

    \App\Models\User::updateOrCreate(
        ['personnel_id' => $battalion->commander_id],
        [
            'name'         => $personnel->full_name,
            'username'     => 'bat_' . $battalion->id,
            'role'         => 'battalion_admin',
            'battalion_id' => $battalion->id,
            'company_id'   => null,
            'password'     => Hash('md5','1234'),
            'personnel_id' => $battalion->commander_id,
        ]
    );
}
}
