<?php

namespace App\Filament\Company\Resources\Warehouses\Tables;

use App\Traits\HasApprovalActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WarehousesTable
{
    use HasApprovalActions;
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
                $query->where('company_id', auth()->user()?->company_id)
                    ->with('responsiblePersonnel')
            )
            ->columns([
                TextColumn::make('item_name')
                    ->label('Item Name')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('item_code')
                    ->label('Item Code')
                    ->placeholder('—'),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->badge()
                    ->color(fn($state) => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')),

                TextColumn::make('unit')
                    ->label('Unit')
                    ->placeholder('—'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Available'    => 'success',
                        'Low Stock'    => 'warning',
                        'Out of Stock' => 'danger',
                        default        => 'gray',
                    }),

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
                        'available' => 'Available',
                        'low'       => 'Low Stock',
                        'out'       => 'Out of Stock',
                    ]),
            ])
            ->headerActions([
                // CreateAction::make()->label('+ Add Item'),
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
