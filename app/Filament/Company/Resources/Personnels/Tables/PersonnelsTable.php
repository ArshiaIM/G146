<?php

namespace App\Filament\Company\Resources\Personnels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PersonnelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
