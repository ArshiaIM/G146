<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Absence;
use App\Models\Leave;
use App\Models\Personnel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $total   = Personnel::count();
        $kadre   = Personnel::where('personnel_type', 'Career')->count();
        $vazife  = Personnel::where('personnel_type', 'Conscription')->count();
        $active  = Personnel::where('status', 'active')->count();
        $leave   = Personnel::where('status', 'leave')->count();
        $medical = Personnel::where('status', 'medical')->count();
        $absent  = Personnel::where('status', 'absent')->count();
        $overdue = Leave::where('status', 'approved')
            ->whereNull('returned_at')
            ->whereDate('end_datetime', '<', today())
            ->count();

        return [
            Stat::make('کل پرسنل تیپ', $total)
                ->description("کادر: $kadre | وظیفه: $vazife")
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('پرسنل فعال', $active)
                ->description($total > 0 ? round(($active/$total)*100) . '٪ از کل' : '0٪')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('مرخصی / بهداری', $leave + $medical)
                ->description("مرخصی: $leave | بهداری: $medical")
                ->icon('heroicon-o-calendar-days')
                ->color('warning'),

            Stat::make('غیبت فعال', $absent)
                ->description($overdue > 0 ? "⚠️ $overdue تأخیر در مراجعت" : 'بدون تأخیر')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($absent > 0 ? 'danger' : 'success'),
        ];
    }
}
