<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Models\Battalion;
use App\Models\Company;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) =>
                $query->with(['personnel', 'battalion', 'company'])
            )
            ->columns([
                TextColumn::make('personnel.full_name')
                    ->label('پرسنل مرتبط')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->personnel
                            ? $record->personnel->rank_label . ' ' . $record->personnel->full_name
                            : '—'
                    )
                    ->placeholder('—'),

                TextColumn::make('username')
                    ->label('نام کاربری')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('نقش')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'super_admin'    => '👑 فرمانده تیپ',
                        'battalion_admin' => '🎖️ فرمانده گردان',
                        'company_admin'  => '🏢 فرمانده گروهان',
                        'operator'       => '💻 اپراتور',
                        default          => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'super_admin'    => 'danger',
                        'battalion_admin' => 'warning',
                        'company_admin'  => 'success',
                        'operator'       => 'primary',
                        default          => 'gray',
                    }),

                TextColumn::make('battalion.name')
                    ->label('گردان')
                    ->placeholder('—'),

                TextColumn::make('company.name')
                    ->label('گروهان')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('نقش')
                    ->options([
                        'super_admin'    => 'فرمانده تیپ',
                        'battalion_admin' => 'فرمانده گردان',
                        'company_admin'  => 'فرمانده گروهان',
                        'operator'       => 'اپراتور',
                    ]),

                SelectFilter::make('battalion_id')
                    ->label('گردان')
                    ->options(Battalion::pluck('name', 'id')),

                SelectFilter::make('company_id')
                    ->label('گروهان')
                    ->options(Company::pluck('name', 'id')),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()->label('ویرایش'),
                \Filament\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('+ کاربر جدید'),
            ]);
    }
}
