<?php

namespace App\Filament\Company\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                 SelectFilter::make('rank_type')
                    ->label('رتبه')
                    ->options([
                        'officer_a'      => 'افسر الف',
                        'officer_b'      => 'افسر ب',
                        'vazife_officer' => 'افسر وظیفه',
                        'nco'            => 'درجه‌دار پایور',
                        'vazife_nco'     => 'درجه‌دار وظیفه',
                        'soldier'        => 'سرباز',
                    ]),

                SelectFilter::make('personnel_type')
                    ->label('نوع پرسنل')
                    ->options([
                        'Career'       => 'کادر',
                        'Conscription' => 'وظیفه',
                    ]),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active'  => 'فعال',
                        'leave'   => 'مرخصی',
                        'medical' => 'بهداری',
                        'absent'  => 'غیبت',
                        'mission' => 'مأمور',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
