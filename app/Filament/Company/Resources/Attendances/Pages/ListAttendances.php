<?php

namespace App\Filament\Company\Resources\Attendances\Pages;

use App\Filament\Company\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use App\Models\OrganizationalStrength;
use App\Models\Personnel;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListAttendances extends ListRecords implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AttendanceResource::class;
    public static array $sharedStats = [];
    public static array $sharedAbsentList = [];


    #[Url]
    public string $selectedDate = '';

    #[Url]
    public string $rankFilter = '';

    public array $statuses = [];
    private function getPersonnelQuery()
    {
        $user = auth()->user();

        if ($user->role === 'battalion_admin') {
            return Personnel::whereHas(
                'company',
                fn($q) =>
                $q->where('battalion_id', $user->battalion_id)
            );
        }

        return Personnel::where('company_id', $user->company_id);
    }

    public function mount(): void
    {
        $this->selectedDate = $this->selectedDate ?: today()->toDateString();
        $this->loadStatuses();
    }

    public function loadStatuses(): void
    {
        $user = auth()->user();

        $companyIds = $user->role === 'battalion_admin'
            ? \App\Models\Company::where('battalion_id', $user->battalion_id)->pluck('id')
            : [$user->company_id];

        $attendances = Attendance::whereIn('company_id', $companyIds)
            ->whereDate('date', $this->selectedDate)
            ->get()
            ->keyBy('personnel_id');

        $this->getPersonnelQuery()
            ->get()
            ->each(function ($p) use ($attendances) {
                $att = $attendances->get($p->id);
                $this->statuses[$p->id] = [
                    'status' => $att?->status ?? 'present',
                    'notes'  => $att?->notes ?? '',
                ];
            });

        self::$sharedStats      = $this->getStats();
        self::$sharedAbsentList = $this->getAbsentList();
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
        foreach ($this->statuses as $personnelId => $data) {
            $personnel = Personnel::find($personnelId);
            if (!$personnel) continue;

            Attendance::updateOrCreate(
                ['personnel_id' => $personnelId, 'date' => $this->selectedDate],
                ['company_id' => $personnel->company_id, 'status' => $data['status'], 'notes' => $data['notes'] ?? '']
            );

            if ($this->selectedDate === today()->toDateString()) {
                $personnel->update([
                    'status' => match ($data['status']) {
                        'present'  => 'active',
                        'mission'  => 'mission',
                        'leave'    => 'leave',
                        'medical'  => 'medical',
                        'absent'   => 'absent',
                        'arrested' => 'absent',
                        'course'   => 'mission',
                        default    => 'active',
                    },
                ]);
            }
        }

        Notification::make()->title('✅ حضور و غیاب ثبت شد')->success()->send();

        self::$sharedStats      = $this->getStats();
        self::$sharedAbsentList = $this->getAbsentList();

        $this->dispatch(
            'attendance-updated',
            stats: self::$sharedStats,
            absentList: self::$sharedAbsentList
        );
    }

    public function getStats(): array
    {
        $companyId = auth()->user()->company_id;
        $strength  = OrganizationalStrength::where('company_id', $companyId)->first();
        $personnel = $this->getPersonnelQuery()->get();
        $ranks     = ['officer_a', 'officer_b', 'vazife_officer', 'nco', 'vazife_nco', 'soldier'];

        $stats = [];

        foreach (['mission', 'leave', 'medical', 'absent', 'arrested', 'course'] as $status) {
            $row = ['total' => 0];
            foreach ($ranks as $rank) {
                $count = $personnel
                    ->where('rank_type', $rank)
                    ->filter(fn($p) => ($this->statuses[$p->id]['status'] ?? 'present') === $status)
                    ->count();
                $row[$rank]   = $count;
                $row['total'] += $count;
            }
            $stats[$status] = $row;
        }

        $presentRow = ['total' => 0];
        foreach ($ranks as $rank) {
            $count = $personnel
                ->where('rank_type', $rank)
                ->filter(fn($p) => ($this->statuses[$p->id]['status'] ?? 'present') === 'present')
                ->count();
            $presentRow[$rank]   = $count;
            $presentRow['total'] += $count;
        }
        $stats['present'] = $presentRow;

        $actualRow = ['total' => $personnel->count()];
        foreach ($ranks as $rank) {
            $actualRow[$rank] = $personnel->where('rank_type', $rank)->count();
        }
        $stats['actual'] = $actualRow;

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
        $personnel = $this->getPersonnelQuery()->get()->keyBy('id');
        $list      = array_fill_keys(['mission', 'leave', 'medical', 'absent', 'arrested', 'course'], []);

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

    // ── Filament Table ──────────────────────────────────────────
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() =>
                $this->getPersonnelQuery()
                    ->orderByDesc('rank')
                    ->when($this->rankFilter, fn($q) => $q->where('rank', $this->rankFilter))
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('نام و نام خانوادگی')
                    ->searchable(['first_name', 'last_name'])
                    ->weight('bold'),

                TextColumn::make('rank_label')
                    ->label('درجه'),

                TextColumn::make('ranktype_label')
                    ->label('رتبه')
                    ->badge()
                    ->color(fn($record) => match ($record->rank_type) {
                        'officer_a'      => 'danger',
                        'officer_b'      => 'warning',
                        'vazife_officer' => 'info',
                        'nco'            => 'success',
                        'vazife_nco'     => 'gray',
                        'soldier'        => 'primary',
                        default          => 'gray',
                    }),

                TextColumn::make('personnel_number')
                    ->label('ک.پرسنلی')
                    ->placeholder('—'),

                TextColumn::make('attendance_status')
                    ->label('وضعیت')
                    ->badge()
                    ->getStateUsing(fn($record) => $this->statuses[$record->id]['status'] ?? 'present')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'present'  => 'حاضر',
                        'mission'  => 'مأمور',
                        'leave'    => 'مرخصی',
                        'medical'  => 'بهداری',
                        'absent'   => 'غیبت',
                        'arrested' => 'بازداشت',
                        'course'   => 'دوره',
                        default    => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'present'  => 'success',
                        'mission'  => 'info',
                        'leave'    => 'warning',
                        'medical'  => 'warning',
                        'absent'   => 'danger',
                        'arrested' => 'danger',
                        'course'   => 'primary',
                        default    => 'gray',
                    }),

                TextColumn::make('attendance_notes')
                    ->label('توضیحات')
                    ->getStateUsing(fn($record) => $this->statuses[$record->id]['notes'] ?? '')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('rank_type')
                    ->label('رتبه')
                    ->options([
                        'officer_a'      => 'افسر الف',
                        'officer_b'      => 'افسر ب',
                        'vazife_officer' => 'افسر وظیفه',
                        'nco'            => 'درجه‌دار پایور',
                        'vazife_nco'     => 'درجه‌دار وظیفه',
                        'soldier'        => 'سرباز',
                    ]),

                SelectFilter::make('personnel_type')
                    ->label('نوع پرسنل')
                    ->options([
                        'Career'       => 'کادر',
                        'Conscription' => 'وظیفه',
                    ]),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active'  => 'فعال',
                        'leave'   => 'مرخصی',
                        'medical' => 'بهداری',
                        'absent'  => 'غیبت',
                        'mission' => 'مأمور',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                // دکمه تغییر وضعیت روی هر ردیف
                Action::make('change_status')
                    ->label('تغییر وضعیت')
                    ->icon('heroicon-o-pencil-square')
                    ->color('gray')
                    ->visible($this->selectedDate === today()->toDateString())
                    ->form([
                        Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'present'  => '✅ حاضر',
                                'mission'  => '📋 مأمور',
                                'leave'    => '🏖️ مرخصی',
                                'medical'  => '🏥 بهداری',
                                'absent'   => '⚠️ غیبت',
                                'arrested' => '🔒 بازداشت',
                                'course'   => '📚 دوره',
                            ])
                            ->default(fn($record) => $this->statuses[$record->id]['status'] ?? 'present')
                            ->required(),

                        TextInput::make('notes')
                            ->label('توضیحات')
                            ->placeholder('مثلاً: مأموریت آموزشی تهران')
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $this->updateStatus($record->id, $data['status'], $data['notes'] ?? '');

                        Notification::make()
                            ->title('وضعیت ' . $record->full_name . ' تغییر کرد')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                // انتخاب تاریخ
                Action::make('select_date')
                    ->label(fn() => 'تاریخ: ' . \Morilog\Jalali\Jalalian::fromCarbon(
                        \Carbon\Carbon::parse($this->selectedDate)
                    )->format('Y/m/d'))
                    ->icon('heroicon-o-calendar')
                    ->color('gray')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->label('انتخاب تاریخ')
                            ->default($this->selectedDate)
                            ->maxDate(today())
                            ->required(),
                    ])
                    ->action(fn(array $data) => $this->changeDate($data['date'])),
            ])
            ->emptyStateHeading('پرسنلی ثبت نشده')
            ->striped();
    }

    // ── جدول آماری به عنوان Infolist ───────────────────────────
    // public function getStatsTableHtml(): \Illuminate\Support\HtmlString
    // {
    //     $stats = $this->getStats();
    //     $absentList = $this->getAbsentList();

    //     $rows = [
    //         'organizational' => 'موجودی سازمانی',
    //         'actual'         => 'موجودی واقعی',
    //         'present'        => 'حاضر',
    //         'mission'        => 'مأمور',
    //         'leave'          => 'مرخصی',
    //         'medical'        => 'بهداری',
    //         'absent'         => 'غیبت',
    //         'arrested'       => 'بازداشت',
    //         'course'         => 'دوره',
    //     ];

    //     $rowStyles = [
    //         'organizational' => 'background:#dbeafe;font-weight:bold;',
    //         'actual'         => 'background:#f3f4f6;font-weight:bold;',
    //         'present'        => 'background:#dcfce7;font-weight:bold;color:#15803d;',
    //         'absent'         => 'background:#fef2f2;color:#dc2626;',
    //         'arrested'       => 'background:#fef2f2;color:#991b1b;',
    //         'mission'        => '',
    //         'leave'          => '',
    //         'medical'        => '',
    //         'course'         => '',
    //     ];

    //     $ranks = ['officer_a', 'officer_b', 'vazife_officer', 'nco', 'vazife_nco', 'soldier'];
    //     $rankLabels = [
    //         'officer_a'      => 'افسر الف',
    //         'officer_b'      => 'افسر ب',
    //         'vazife_officer' => 'افسر وظیفه',
    //         'nco'            => 'د.پایور',
    //         'vazife_nco'     => 'د.وظیفه',
    //         'soldier'        => 'سرباز',
    //     ];

    //     $absentLabels = [
    //         'mission'  => 'مأموران',
    //         'leave'    => 'مرخصی',
    //         'medical'  => 'بهداری',
    //         'absent'   => 'غیبت',
    //         'arrested' => 'بازداشت',
    //         'course'   => 'دوره',
    //     ];

    //     // ── جدول آماری ──
    //     $html = '<div style="direction:rtl;font-family:inherit;">';
    //     $html .= '<div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-bottom:16px;">';
    //     $html .= '<div style="background:#1f2937;color:white;padding:8px 16px;font-weight:bold;text-align:center;">جدول آمار نفرات</div>';
    //     $html .= '<div style="overflow-x:auto;">';
    //     $html .= '<table style="width:100%;border-collapse:collapse;font-size:12px;text-align:center;">';
    //     $html .= '<thead style="background:#f9fafb;">';
    //     $html .= '<tr>';
    //     $html .= '<th style="padding:8px;border:1px solid #e5e7eb;text-align:right;">وضعیت</th>';
    //     foreach ($rankLabels as $label) {
    //         $html .= "<th style='padding:8px;border:1px solid #e5e7eb;'>$label</th>";
    //     }
    //     $html .= '<th style="padding:8px;border:1px solid #e5e7eb;font-weight:bold;">جمع</th>';
    //     $html .= '</tr></thead><tbody>';

    //     foreach ($rows as $key => $label) {
    //         $style = $rowStyles[$key] ?? '';
    //         $html .= "<tr style='$style'>";
    //         $html .= "<td style='padding:8px;border:1px solid #e5e7eb;text-align:right;font-weight:500;'>$label</td>";
    //         foreach ($ranks as $rank) {
    //             $val = $stats[$key][$rank] ?? 0;
    //             $html .= "<td style='padding:8px;border:1px solid #e5e7eb;'>$val</td>";
    //         }
    //         $total = $stats[$key]['total'] ?? 0;
    //         $html .= "<td style='padding:8px;border:1px solid #e5e7eb;font-weight:bold;'>$total</td>";
    //         $html .= '</tr>';
    //     }

    //     $html .= '</tbody></table></div></div>';

    //     // ── لیست اسامی غیرحاضر ──
    //     $html .= '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">';
    //     foreach ($absentLabels as $status => $label) {
    //         $people = $absentList[$status] ?? [];
    //         if (count($people) === 0) continue;

    //         $html .= '<div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">';
    //         $html .= "<div style='background:#f3f4f6;padding:8px 12px;font-weight:bold;font-size:12px;display:flex;justify-content:space-between;'>";
    //         $html .= "<span>$label</span><span style='background:white;border-radius:999px;padding:0 8px;font-size:11px;'>" . count($people) . "</span></div>";
    //         $html .= '<ul style="list-style:none;margin:0;padding:0;">';
    //         foreach ($people as $person) {
    //             $html .= '<li style="padding:6px 12px;border-top:1px solid #f3f4f6;font-size:12px;">';
    //             $html .= "<span style='color:#9ca3af;font-size:11px;'>{$person['rank']}</span> ";
    //             $html .= "<strong>{$person['name']}</strong>";
    //             if ($person['notes']) {
    //                 $html .= "<div style='color:#9ca3af;font-size:11px;'>{$person['notes']}</div>";
    //             }
    //             $html .= '</li>';
    //         }
    //         $html .= '</ul></div>';
    //     }
    //     $html .= '</div>';
    //     $html .= '</div>';

    //     return new \Illuminate\Support\HtmlString($html);
    // }

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

            Action::make('show_stats')
                ->label('📊 جدول آمار')
                ->color('info')
                ->modalHeading('جدول آمار نفرات')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('بستن')
                ->modalContent(function () {
                    $stats = $this->getStats();
                    $absentList = $this->getAbsentList();

                    $ranks = [
                        'officer_a'      => 'افسر الف',
                        'officer_b'      => 'افسر ب',
                        'vazife_officer' => 'افسر وظیفه',
                        'nco'            => 'د.پایور',
                        'vazife_nco'     => 'د.وظیفه',
                        'soldier'        => 'سرباز',
                        'total'          => 'جمع',
                    ];

                    $statuses = [
                        'organizational' => 'سازمانی',
                        'actual'         => 'موجودی',
                        'mission'        => 'مأمور',
                        'leave'          => 'مرخصی',
                        'medical'        => 'بهداری',
                        'absent'         => 'غیبت',
                        'arrested'       => 'بازداشت',
                        'course'         => 'دوره',
                        'misc'           => 'متفرقه',
                        'present'        => 'حاضر',
                    ];

                    $statusStyles = [
                        'organizational' => 'background:#dbeafe;font-weight:bold;',
                        'actual'         => 'background:#f3f4f6;font-weight:bold;',
                        'present'        => 'background:#dcfce7;font-weight:bold;color:#15803d;',
                        'absent'         => 'color:#dc2626;',
                        'arrested'       => 'color:#991b1b;',
                        'mission'        => '',
                        'leave'          => '',
                        'medical'        => '',
                        'course'         => '',
                    ];

                    $absentLabels = [
                        'mission'  => 'مأموران',
                        'leave'    => 'مرخصی',
                        'medical'  => 'بهداری',
                        'absent'   => 'غیبت',
                        'arrested' => 'بازداشت',
                        'course'   => 'دوره',
                    ];

                    $html = '<div style="direction:rtl;">';

                    // ── جدول آماری ──
                    $html .= '<table style="width:100%;border-collapse:collapse;font-size:12px;text-align:center;margin-bottom:20px;">';

                    // هدر — ستون‌ها وضعیت‌ها هستن
                    $html .= '<thead><tr style="background:#1f2937;color:white;">';
                    $html .= '<th style="padding:8px;border:1px solid #374151;text-align:right;">رتبه</th>';
                    foreach ($statuses as $label) {
                        $html .= "<th style='padding:8px;border:1px solid #374151;'>$label</th>";
                    }
                    $html .= '</tr></thead><tbody>';

                    // هر سطر یه رتبه
                    foreach ($ranks as $rankKey => $rankLabel) {
                        $isTotal = $rankKey === 'total';
                        $rowStyle = $isTotal ? 'background:#1f2937;color:white;font-weight:bold;' : '';

                        $html .= "<tr style='$rowStyle'>";
                        $html .= "<td style='padding:8px;border:1px solid #e5e7eb;text-align:right;font-weight:bold;'>$rankLabel</td>";

                        foreach (array_keys($statuses) as $statusKey) {
                            $val = $isTotal
                                ? ($stats[$statusKey]['total'] ?? 0)
                                : ($stats[$statusKey][$rankKey] ?? 0);

                            $cellStyle = 'padding:8px;border:1px solid #e5e7eb;';

                            if ($statusKey === 'present' && !$isTotal) {
                                $cellStyle .= 'background:#dcfce7;color:#15803d;font-weight:bold;';
                            } elseif ($statusKey === 'misc' && !$isTotal) {
                                $cellStyle .= 'background:#fef9c3;color:#854d0e;font-weight:bold;';
                            } elseif (in_array($statusKey, ['absent', 'arrested']) && $val > 0) {
                                $cellStyle .= 'color:#dc2626;font-weight:bold;';
                            }

                            $html .= "<td style='$cellStyle'>$val</td>";
                        }

                        $html .= '</tr>';
                    }

                    $html .= '</tbody></table>';

                    // ── لیست اسامی غیرحاضر ──
                    $html .= '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">';
                    foreach ($absentLabels as $status => $label) {
                        $people = $absentList[$status] ?? [];
                        if (count($people) === 0) continue;

                        $html .= "<div style='border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;'>";
                        $html .= "<div style='color:#000;background:#f3f4f6;padding:6px 12px;font-weight:bold;font-size:12px;display:flex;justify-content:space-between;'>";
                        $html .= "<span>$label</span><span style='background:white;border-radius:999px;padding:0 8px;'>" . count($people) . "</span></div>";
                        $html .= "<ul style='margin:0;padding:0;list-style:none;'>";
                        foreach ($people as $person) {
                            $html .= "<li style='padding:5px 12px;border-top:1px solid #f3f4f6;font-size:12px;'>";
                            $html .= "<span style='color:#9ca3af;font-size:11px;'>{$person['rank']}</span> <strong>{$person['name']}</strong>";
                            if ($person['notes']) {
                                $html .= "<div style='color:#6b7280;font-size:11px;'>{$person['notes']}</div>";
                            }
                            $html .= '</li>';
                        }
                        $html .= '</ul></div>';
                    }
                    $html .= '</div>';

                    $html .= '</div>';

                    return new \Illuminate\Support\HtmlString($html);
                }),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Company\Widgets\AttendanceStatsWidget::class,
        ];
    }

    // جدول آماری رو بالای Table نشون بده
    protected function getTableContentFooter(): ?\Illuminate\Contracts\View\View
    {
        return null;
    }

    public function getTitle(): string
    {
        $jalali = \Morilog\Jalali\Jalalian::fromCarbon(
            \Carbon\Carbon::parse($this->selectedDate)
        )->format('Y/m/d');

        return "حضور و غیاب — $jalali";
    }

    // نمایش جدول آماری بالای لیست پرسنل
    protected function getTableDescription(): ?string
    {
        return null;
    }

    public function getTableHeader(): ?\Illuminate\Contracts\View\View
    {
        return null;
    }

    // inject جدول آماری به عنوان content قبل از table
    public function getTableEmptyStateDescription(): ?string
    {
        return 'پرسنلی برای این گروهان ثبت نشده';
    }

    protected function mutateTableQueryUsing(Builder $query): Builder
    {
        return $query
            ->where('company_id', auth()->user()->company_id)
            ->when($this->rankFilter, fn($q) => $q->where('rank_type', $this->rankFilter));
    }
}
