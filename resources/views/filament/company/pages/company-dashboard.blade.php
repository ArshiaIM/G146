<x-filament-panels::page>
@php
    $data = $this->getViewData();
    extract($data);
@endphp

<div class="space-y-6" dir="rtl">

    {{-- ── کارت‌های آماری ────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-4 border-r-4 border-blue-500">
            <div class="text-3xl font-bold text-blue-600">{{ $total }}</div>
            <div class="text-sm text-gray-500 mt-1">کل پرسنل</div>
            <div class="text-xs text-gray-400 mt-1">کادر: {{ $kadre }} | وظیفه: {{ $vazife }}</div>
        </div>

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-4 border-r-4 border-green-500">
            <div class="text-3xl font-bold text-green-600">{{ $presentToday }}</div>
            <div class="text-sm text-gray-500 mt-1">حاضرین امروز</div>
            <div class="text-xs text-gray-400 mt-1">از {{ $total }} نفر</div>
        </div>

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-4 border-r-4 border-yellow-500">
            <div class="text-3xl font-bold text-yellow-600">{{ $onLeave }}</div>
            <div class="text-sm text-gray-500 mt-1">در مرخصی</div>
            @if($overdueLeaves->count() > 0)
                <div class="text-xs text-red-500 mt-1">⚠️ {{ $overdueLeaves->count() }} نفر تأخیر دارن</div>
            @endif
        </div>

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-4 border-r-4 border-red-500">
            <div class="text-3xl font-bold text-red-600">{{ $activeAbsences }}</div>
            <div class="text-sm text-gray-500 mt-1">غیبت / فرار فعال</div>
            <div class="text-xs text-gray-400 mt-1">بهداری: {{ $inMedical }}</div>
        </div>

    </div>

    {{-- ── هشدارهای مهم ───────────────────────────────────── --}}
    @if($activeAbsences > 0 || $overdueLeaves->count() > 0)
    <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
        <div class="font-bold text-red-700 dark:text-red-400 mb-2">🚨 هشدارهای فوری</div>
        <ul class="space-y-1 text-sm text-red-600 dark:text-red-300">
            @foreach($absenceList as $abs)
                <li>• {{ $abs->personnel->rank_label }} {{ $abs->personnel->full_name }}
                    — {{ $abs->type === 'escaped' ? 'فرار' : 'غیبت' }}
                    ({{ $abs->duration_days }} روز)</li>
            @endforeach
            @foreach($overdueLeaves as $leave)
                <li>• {{ $leave->personnel->rank_label }} {{ $leave->personnel->full_name }}
                    — تأخیر در مراجعت از مرخصی</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ── پرسنل در مرخصی ────────────────────────────── --}}
        @if($onLeaveList->count() > 0)
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow overflow-hidden">
            <div class="bg-blue-50 dark:bg-blue-900/30 px-4 py-3 font-bold text-blue-700 dark:text-blue-300">
                🏖️ پرسنل در مرخصی ({{ $onLeaveList->count() }} نفر)
            </div>
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-400 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-right">نام</th>
                        <th class="px-4 py-2 text-right">نوع</th>
                        <th class="px-4 py-2 text-right">پایان</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($onLeaveList as $leave)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2">
                            <span class="text-xs text-gray-400">{{ $leave->personnel->rank_label }}</span>
                            {{ $leave->personnel->full_name }}
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ $leave->type_label }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $leave->end_jalali }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ── پرسنل در بهداری ────────────────────────────── --}}
        @if($inMedicalList->count() > 0)
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow overflow-hidden">
            <div class="bg-yellow-50 dark:bg-yellow-900/30 px-4 py-3 font-bold text-yellow-700 dark:text-yellow-300">
                🏥 پرسنل در بهداری ({{ $inMedicalList->count() }} نفر)
            </div>
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-400 border-b dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-right">نام</th>
                        <th class="px-4 py-2 text-right">تشخیص</th>
                        <th class="px-4 py-2 text-right">روز بستری</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($inMedicalList as $med)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2">
                            <span class="text-xs text-gray-400">{{ $med->personnel->rank_label }}</span>
                            {{ $med->personnel->full_name }}
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ $med->diagnosis ?? '—' }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-0.5 rounded text-xs {{ $med->days_in_medical > 5 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $med->days_in_medical }} روز
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>

    {{-- ── نگهبانی امروز ──────────────────────────────────── --}}
    @if($guardToday)
    <div class="rounded-xl bg-white dark:bg-gray-800 shadow overflow-hidden">
        <div class="bg-gray-100 dark:bg-gray-700 px-4 py-3 font-bold">
            🛡️ برنامه نگهبانی امروز
        </div>
        @php $byPost = $guardToday->shifts->groupBy('guard_post_id'); @endphp
        <div class="p-4 grid grid-cols-1 md:grid-cols-{{ min($byPost->count(), 3) }} gap-4">
            @foreach($byPost as $shifts)
            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700/50 px-3 py-2 text-sm font-bold">
                    📍 {{ $shifts->first()->post->name }}
                </div>
                <div class="divide-y dark:divide-gray-700">
                    @foreach($shifts as $shift)
                    <div class="px-3 py-2 text-sm flex justify-between items-center">
                        <span class="text-gray-500 font-mono text-xs">{{ $shift->start_time }}–{{ $shift->end_time }}</span>
                        <span>{{ $shift->personnel->full_name }}</span>
                        <span class="text-xs text-gray-400">{{ $shift->shift_label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 p-6 text-center text-gray-400 text-sm">
        🛡️ برنامه نگهبانی برای امروز ثبت نشده
        <a href="{{ route('filament.company.resources.guards.create') }}" class="text-blue-500 mr-2 hover:underline">ثبت کنید</a>
    </div>
    @endif

</div>
</x-filament-panels::page>
