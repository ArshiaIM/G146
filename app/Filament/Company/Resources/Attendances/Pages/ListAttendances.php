<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Models\OrganizationalStrength;
use App\Models\Personnel;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    #[Url]
    public string $selectedDate = '';

    #[Url]
    public string $rankFilter = '';

    public array $statuses = [];

    public function mount(): void
    {
        $this->selectedDate = $this->selectedDate ?: today()->toDateString();
        $this->loadStatuses();
    }

    public function loadStatuses(): void
    {
        $companyId = auth()->user()->company_id;

        $attendances = Attendance::where('company_id', $companyId)
            ->whereDate('date', $this->selectedDate)
            ->get()
            ->keyBy('personnel_id');

        $personnel = Personnel::where('company_id', $companyId)
            ->orderBy('rank')
            ->get();

        $this->statuses = [];
        foreach ($personnel as $p) {
            $att = $attendances->get($p->id);
            $this->statuses[$p->id] = [
                'status' => $att?->status ?? 'present',
                'notes'  => $att?->notes ?? '',
            ];
        }
    }

    public function changeDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->loadStatuses();
    }

    public function updateStatus(int $personnelId, string $status, string $notes = ''): void
    {
        $this->statuses[$personnelId] = [
            'status' => $status,
            'notes'  => $notes,
        ];
    }

    public function saveAttendance(): void
    {
        $companyId = auth()->user()->company_id;

        foreach ($this->statuses as $personnelId => $data) {
            Attendance::updateOrCreate(
                [
                    'personnel_id' => $personnelId,
                    'date'         => $this->selectedDate,
                ],
                [
                    'company_id' => $companyId,
                    'status'     => $data['status'],
                    'notes'      => $data['notes'] ?? '',
                ]
            );

            if ($this->selectedDate === today()->toDateString()) {
                $statusMap = [
                    'present'  => 'active',
                    'mission'  => 'mission',
                    'leave'    => 'leave',
                    'medical'  => 'medical',
                    'absent'   => 'absent',
                    'arrested' => 'absent',
                    'course'   => 'mission',
                ];
                Personnel::find($personnelId)?->update([
                    'status' => $statusMap[$data['status']] ?? 'active',
                ]);
            }
        }

        Notification::make()
            ->title('✅ حضور و غیاب ثبت شد')
            ->success()
            ->send();
    }

    public function getStats(): array
    {
        $companyId = auth()->user()->company_id;
        $strength  = OrganizationalStrength::where('company_id', $companyId)->first();
        $personnel = Personnel::where('company_id', $companyId)->get();

        $ranks    = ['officer_a', 'officer_b', 'vazife_officer', 'nco', 'vazife_nco', 'soldier'];
        $statuses = ['mission', 'leave', 'medical', 'absent', 'arrested', 'course'];

        $stats = [];

        foreach ($statuses as $status) {
            $row = ['total' => 0];
            foreach ($ranks as $rank) {
                $count = $personnel
                    ->where('rank', $rank)
                    ->filter(fn($p) => ($this->statuses[$p->id]['status'] ?? 'present') === $status)
                    ->count();
                $row[$rank]  = $count;
                $row['total'] += $count;
            }
            $stats[$status] = $row;
        }

        // حاضر
        $presentRow = ['total' => 0];
        foreach ($ranks as $rank) {
            $count = $personnel
                ->where('rank', $rank)
                ->filter(fn($p) => ($this->statuses[$p->id]['status'] ?? 'present') === 'present')
                ->count();
            $presentRow[$rank]  = $count;
            $presentRow['total'] += $count;
        }
        $stats['present'] = $presentRow;

        // موجودی واقعی
        $actualRow = ['total' => $personnel->count()];
        foreach ($ranks as $rank) {
            $actualRow[$rank] = $personnel->where('rank', $rank)->count();
        }
        $stats['actual'] = $actualRow;

        // موجودی سازمانی
        $orgRow = ['total' => $strength?->getTotal() ?? 0];
        foreach ($ranks as $rank) {
            $orgRow[$rank] = $strength?->$rank ?? 0;
        }
        $stats['organizational'] = $orgRow;

        return $stats;
    }

    public function getAbsentList(): array
    {
        $companyId = auth()->user()->company_id;
        $personnel = Personnel::where('company_id', $companyId)->get()->keyBy('id');

        $list = [
            'mission'  => [],
            'leave'    => [],
            'medical'  => [],
            'absent'   => [],
            'arrested' => [],
            'course'   => [],
        ];

        foreach ($this->statuses as $personnelId => $data) {
            $status = $data['status'];
            if ($status !== 'present' && isset($list[$status])) {
                $p = $personnel->get($personnelId);
                if ($p) {
                    $list[$status][] = [
                        'name'  => $p->full_name,
                        'rank'  => $p->rank_label,
                        'notes' => $data['notes'] ?? '',
                    ];
                }
            }
        }

        return $list;
    }

    public function getPersonnel()
    {
        $query = Personnel::where('company_id', auth()->user()->company_id)
            ->orderBy('rank');

        if ($this->rankFilter) {
            $query->where('rank', $this->rankFilter);
        }

        return $query->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('💾 ثبت آمار')
                ->color('success')
                ->action(fn() => $this->saveAttendance()),

            Action::make('print')
                ->label('🖨️ چاپ')
                ->color('gray')
                ->extraAttributes(['onclick' => 'window.print()', 'type' => 'button']),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'stats'      => $this->getStats(),
            'absentList' => $this->getAbsentList(),
            'personnel'  => $this->getPersonnel(),
            'rankFilter' => $this->rankFilter,
            'statuses'   => $this->statuses,
            'selectedDate' => $this->selectedDate,
        ];
    }

    // override کردن view پیش‌فرض ListRecords
    public function getView(): string
    {
        return 'filament.company.pages.attendance';
    }
}
