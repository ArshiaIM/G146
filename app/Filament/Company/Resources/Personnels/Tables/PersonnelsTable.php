<?php

namespace App\Filament\Company\Resources\Personnels\Tables;

use App\Traits\HasApprovalActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class PersonnelsTable
{
    use HasApprovalActions;
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                $query->orderByDesc('rank');
                if ($user->role === 'battalion_admin') {
                    // دسترسی به همه گروهان‌های گردان
                    return $query->whereHas(
                        'company',
                        fn($q) =>
                        $q->where('battalion_id', $user->battalion_id)
                    );
                }


                // دسترسی فقط به گروهان خودش
                return $query->where('company_id', $user->company_id);
            })
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('full_name')
                    ->label('نام و نام خانوادگی')
                    ->searchable(['first_name', 'last_name']),

                \Filament\Tables\Columns\TextColumn::make('rank_label')
                    ->label('درجه'),

                \Filament\Tables\Columns\TextColumn::make('ranktype_label')
                    ->label('رتبه'),


                \Filament\Tables\Columns\TextColumn::make('personnel_type_label')
                    ->label('نوع'),

                \Filament\Tables\Columns\TextColumn::make('status_label')
                    ->label('وضعیت'),

                \Filament\Tables\Columns\TextColumn::make('national_code')
                    ->label('کد ملی'),
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
                EditAction::make()->label('ویرایش'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف'),
                    ForceDeleteBulkAction::make()->label('حذف دائم'),
                    RestoreBulkAction::make()->label('بازیابی'),
                ]),
            ]);
    }
}
