<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Battalion;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BattalionStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = false;
    protected static ?string $heading = '🏛️ وضعیت گردان‌ها';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Battalion::with(['companies.personnel']))
            ->columns([
                TextColumn::make('name')
                    ->label('گردان')
                    ->weight('bold'),

                TextColumn::make('companies_count')
                    ->label('گروهان‌ها')
                    ->counts('companies')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_personnel')
                    ->label('کل پرسنل')
                    ->getStateUsing(fn($record) => $record->personnel()->count()),

                TextColumn::make('active_personnel')
                    ->label('فعال')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'active')->count())
                    ->color('success'),

                TextColumn::make('leave_personnel')
                    ->label('مرخصی')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'leave')->count())
                    ->color('info'),

                TextColumn::make('medical_personnel')
                    ->label('بهداری')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'medical')->count())
                    ->color('warning'),

                TextColumn::make('absent_personnel')
                    ->label('غیبت')
                    ->getStateUsing(fn($record) => $record->personnel()->where('status', 'absent')->count())
                    ->color('danger'),

                TextColumn::make('readiness')
                    ->label('آمادگی')
                    ->getStateUsing(function($record) {
                        $total  = $record->personnel()->count();
                        $active = $record->personnel()->where('status', 'active')->count();
                        return $total > 0 ? round(($active/$total)*100) . '٪' : '0٪';
                    })
                    ->badge()
                    ->color(function($record) {
                        $total  = $record->personnel()->count();
                        $active = $record->personnel()->where('status', 'active')->count();
                        $pct    = $total > 0 ? round(($active/$total)*100) : 0;
                        return $pct >= 80 ? 'success' : ($pct >= 60 ? 'warning' : 'danger');
                    }),
            ])
            ->paginated(false);
    }
}
