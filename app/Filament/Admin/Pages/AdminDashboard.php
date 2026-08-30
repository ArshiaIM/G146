<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\AlertsWidget;
use App\Filament\Admin\Widgets\BattalionStatsWidget;
use App\Filament\Admin\Widgets\StatsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;

class AdminDashboard extends Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'داشبورد';
    protected static ?string $slug            = 'dashboard';
    protected static ?int    $navigationSort  = 0;

    public function getTitle(): string
    {
        return 'داشبورد فرماندهی تیپ ۶';
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            AlertsWidget::class,
            BattalionStatsWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}
