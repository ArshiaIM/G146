<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Absence;
use App\Models\Leave;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AlertsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;
    protected static ?string $heading = '🚨 هشدارهای فوری';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Absence::whereNull('returned_at')
                    ->with('personnel.company.battalion')
            )
            ->columns([
                TextColumn::make('personnel.full_name')
                    ->label('نام پرسنل')
                    ->weight('bold'),

                TextColumn::make('personnel.rank_label')
                    ->label('رتبه'),

                TextColumn::make('personnel.company.name')
                    ->label('گروهان'),

                TextColumn::make('personnel.company.battalion.name')
                    ->label('گردان'),

                TextColumn::make('type')
                    ->label('نوع')
                    ->formatStateUsing(fn($state) => $state === 'escaped' ? '🚨 فرار' : '⚠️ غیبت')
                    ->badge()
                    ->color(fn($state) => $state === 'escaped' ? 'danger' : 'warning'),

                TextColumn::make('duration_days')
                    ->label('مدت (روز)')
                    ->suffix(' روز')
                    ->color(fn($state) => $state > 3 ? 'danger' : 'warning'),

                TextColumn::make('started_jalali')
                    ->label('از تاریخ'),
            ])
            ->emptyStateHeading('✅ هیچ غیبتی ثبت نشده')
            ->paginated(false);
    }
}
