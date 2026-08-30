<?php

namespace App\Filament\Company\Resources\MotorPools\Tables;

use App\Traits\HasApprovalActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MotorPoolsTable
{
    use HasApprovalActions;
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
                $query->where('company_id', auth()->user()?->company_id)
                    ->with(['driver', 'responsiblePersonnel'])
            )
            ->columns([
                TextColumn::make('vehicle_type')
                    ->label('Vehicle Type')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('plate_number')
                    ->label('Plate Number')
                    ->placeholder('—'),

                TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->placeholder('—'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Active'           => 'success',
                        'Under Repair'     => 'warning',
                        'Out of Service'   => 'danger',
                        default            => 'gray',
                    }),

                TextColumn::make('driver.full_name')
                    ->label('Driver')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->driver
                            ? $record->driver->rank_label . ' ' . $record->driver->full_name
                            : '—'
                    )
                    ->placeholder('—'),

                TextColumn::make('responsiblePersonnel.full_name')
                    ->label('Responsible')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->responsiblePersonnel
                            ? $record->responsiblePersonnel->rank_label . ' ' . $record->responsiblePersonnel->full_name
                            : '—'
                    )
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Active',
                        'repair'   => 'Under Repair',
                        'inactive' => 'Out of Service',
                    ]),
            ])
            ->headerActions([
                // CreateAction::make()->label('+ Add Vehicle'),
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
