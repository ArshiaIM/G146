<?php

namespace App\Filament\Company\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class AttendanceStatsWidget extends Widget
{
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

     protected string $view = 'filament.company.pages.attendance-stats';

    public array $stats = [];
    public array $absentList = [];

    public function mount(): void
    {
        // داده‌های اولیه رو از ListAttendances میگیره
        $this->stats = \App\Filament\Company\Resources\Attendances\Pages\ListAttendances::$sharedStats;
        $this->absentList = \App\Filament\Company\Resources\Attendances\Pages\ListAttendances::$sharedAbsentList;
    }

    #[On('attendance-updated')]
    public function updateStats(array $stats, array $absentList): void
    {
        $this->stats = $stats;
        $this->absentList = $absentList;
    }
}
