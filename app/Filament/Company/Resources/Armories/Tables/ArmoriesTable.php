<?php

namespace App\Filament\Company\Resources\Armories\Tables;

use App\Traits\HasApprovalActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArmoriesTable
{
    use HasApprovalActions;
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where('company_id', auth()->user()?->company_id)
                    ->with('responsiblePersonnel')
            )
            ->columns([
                TextColumn::make('weapon_type')
                    ->label('نوع سلاح')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('serial_number')
                    ->label('شماره سریال')
                    ->placeholder('—'),

                TextColumn::make('quantity')
                    ->label('تعداد')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('status_label')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'فعال'     => 'success',
                        'در تعمیر' => 'warning',
                        'مفقود'    => 'danger',
                        default    => 'gray',
                    }),

                TextColumn::make('responsiblePersonnel.full_name')
                    ->label('مسئول')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->responsiblePersonnel
                            ? $record->responsiblePersonnel->rank_label . ' ' . $record->responsiblePersonnel->full_name
                            : '—'
                    )
                    ->placeholder('—'),

                TextColumn::make('notes')
                    ->label('یادداشت')
                    ->limit(30)
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active' => 'فعال',
                        'repair' => 'در تعمیر',
                        'lost'   => 'مفقود',
                    ]),
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
