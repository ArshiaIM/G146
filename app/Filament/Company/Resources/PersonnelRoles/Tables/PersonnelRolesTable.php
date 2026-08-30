<?php

namespace App\Filament\Company\Resources\PersonnelRoles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonnelRolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
         ->modifyQueryUsing(fn(Builder $query) =>
                $query->where('company_id', auth()->user()?->company_id)
                    ->with('personnel')
            )
            ->columns([
                TextColumn::make('personnel.full_name')
                    ->label('Personnel')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->personnel
                            ? $record->personnel->rank_label . ' ' . $record->personnel->full_name
                            : '—'
                    )
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('personnel.rank_type_label')
                    ->label('Rank Type')
                    ->badge(),

                TextColumn::make('role_title')
                    ->label('Role')
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Position')
                    ->placeholder('—'),

                TextColumn::make('responsibilities')
                    ->label('Responsibilities')
                    ->limit(40)
                    ->placeholder('—'),

                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                SelectFilter::make('personnel_id')
                    ->label('Personnel')
                    ->options(fn() =>
                        \App\Models\Personnel::where('company_id', auth()->user()?->company_id)
                            ->get()
                            ->mapWithKeys(fn($p) => [
                                $p->id => $p->rank_label . ' ' . $p->full_name
                            ])
                    )
                    ->searchable(),

                SelectFilter::make('is_primary')
                    ->label('Role Type')
                    ->options([
                        '1' => 'Primary',
                        '0' => 'Secondary',
                    ]),
            ])
            ->headerActions([
                // CreateAction::make()->label('+ Assign Role'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('personnel_id');
    }
}
