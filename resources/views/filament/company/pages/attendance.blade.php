<x-filament-panels::page>
<div class="space-y-6" dir="rtl">

    {{-- انتخاب تاریخ --}}
    <div class="flex items-center gap-3 flex-wrap bg-white dark:bg-gray-800 rounded-xl p-4 shadow">
        <span class="font-bold text-sm">تاریخ:</span>
        <input
            type="date"
            value="{{ $selectedDate }}"
            max="{{ today()->toDateString() }}"
            wire:change="changeDate($event.target.value)"
            class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600"
        />
        <span class="text-sm text-gray-500 font-mono">
            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($selectedDate))->format('Y/m/d') }}
        </span>
        @if($selectedDate === today()->toDateString())
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">امروز</span>
        @else
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">⚠️ آمار گذشته — فقط نمایش</span>
        @endif
    </div>

    {{-- جدول آماری --}}
    <div class="rounded-xl overflow-hidden shadow border dark:border-gray-700">
        <div class="bg-gray-800 text-white px-4 py-2 font-bold text-center text-sm print:bg-white print:text-black print:border-b">

            {{-- جدول آمار نفرات — {{ auth()->user()->company->name }} — {{ auth()->user()->company->battalion->name }} --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-center">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 text-right border dark:border-gray-600">وضعیت</th>
                        <th class="px-3 py-2 border dark:border-gray-600">افسر الف</th>
                        <th class="px-3 py-2 border dark:border-gray-600">افسر ب</th>
                        <th class="px-3 py-2 border dark:border-gray-600">افسر وظیفه</th>
                        <th class="px-3 py-2 border dark:border-gray-600">د.پایور</th>
                        <th class="px-3 py-2 border dark:border-gray-600">د.وظیفه</th>
                        <th class="px-3 py-2 border dark:border-gray-600">سرباز</th>
                        <th class="px-3 py-2 border dark:border-gray-600 font-bold">جمع</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rows = [
                            'organizational' => ['label' => 'موجودی سازمانی', 'class' => 'bg-blue-50 dark:bg-blue-900/30 font-bold'],
                            'actual'         => ['label' => 'موجودی واقعی',   'class' => 'bg-gray-50 dark:bg-gray-800 font-bold'],
                            'present'        => ['label' => 'حاضر',           'class' => 'bg-green-50 dark:bg-green-900/30 font-bold text-green-700'],
                            'mission'        => ['label' => 'مأمور',          'class' => ''],
                            'leave'          => ['label' => 'مرخصی',          'class' => ''],
                            'medical'        => ['label' => 'بهداری',         'class' => ''],
                            'absent'         => ['label' => 'غیبت',           'class' => 'bg-red-50 dark:bg-red-900/20 text-red-600'],
                            'arrested'       => ['label' => 'بازداشت',        'class' => 'bg-red-50 dark:bg-red-900/20 text-red-600'],
                            'course'         => ['label' => 'دوره',           'class' => ''],
                        ];
                        $rankKeys = ['officer_a','officer_b','vazife_officer','nco','vazife_nco','soldier'];
                    @endphp

                    @foreach($rows as $key => $row)
                    <tr class="{{ $row['class'] }}">
                        <td class="px-3 py-2 text-right border dark:border-gray-700 font-medium">{{ $row['label'] }}</td>
                        @foreach($rankKeys as $rank)
                        <td class="px-3 py-2 border dark:border-gray-700">
                            {{ $stats[$key][$rank] ?? 0 }}
                        </td>
                        @endforeach
                        <td class="px-3 py-2 border dark:border-gray-700 font-bold">
                            {{ $stats[$key]['total'] ?? 0 }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- لیست اسامی غیرحاضر --}}
    @php
        $absentLabels = [
            'mission'  => 'مأموران',
            'leave'    => 'مرخصی',
            'medical'  => 'بهداری',
            'absent'   => 'غیبت',
            'arrested' => 'بازداشت',
            'course'   => 'دوره',
        ];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($absentLabels as $status => $label)
        @if(count($absentList[$status]) > 0)
        <div class="rounded-lg border dark:border-gray-700 overflow-hidden text-sm">
            <div class="bg-gray-100 dark:bg-gray-700 px-3 py-2 font-bold flex justify-between">
                <span>{{ $label }}</span>
                <span class="bg-white dark:bg-gray-800 text-xs px-2 rounded-full">{{ count($absentList[$status]) }}</span>
            </div>
            <ul class="divide-y dark:divide-gray-700">
                @foreach($absentList[$status] as $person)
                <li class="px-3 py-1.5">
                    <span class="text-xs text-gray-400">{{ $person['rank_type'] }}</span>
                    <span class="font-medium mr-1">{{ $person['name'] }}</span>
                    @if($person['notes'])
                    <div class="text-xs text-gray-400">{{ $person['notes'] }}</div>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @endforeach
    </div>

    {{-- لیست پرسنل برای ثبت وضعیت --}}
    @if($selectedDate === today()->toDateString())
    <div class="rounded-xl border dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-100 dark:bg-gray-700 px-4 py-3 font-bold text-sm">
            ثبت وضعیت پرسنل
        </div>

        {{-- فیلتر رتبه --}}
        <div class="px-4 py-2 flex gap-2 flex-wrap border-b dark:border-gray-700">
            @foreach(['' => 'همه', 'officer_a' => 'افسر الف', 'officer_b' => 'افسر ب', 'vazife_officer' => 'افسر وظیفه', 'nco' => 'د.پایور', 'vazife_nco' => 'د.وظیفه', 'soldier' => 'سرباز'] as $val => $lbl)
            <button
                wire:click="$set('rankFilter', '{{ $val }}')"
                class="text-xs px-3 py-1 rounded-full border transition
                    {{ $rankFilter === $val ? 'bg-blue-600 text-white border-blue-600' : 'dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-400 bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-right">نام</th>
                        <th class="px-4 py-2 text-right">رتبه</th>
                        <th class="px-4 py-2 text-right">ک.پرسنلی</th>
                        <th class="px-4 py-2 text-center">وضعیت</th>
                        <th class="px-4 py-2 text-right">توضیحات</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($personnel as $p)
                    @php $currentStatus = $statuses[$p->id]['status'] ?? 'present'; @endphp
                    <tr class="{{ $currentStatus !== 'present' ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                        <td class="px-4 py-2 font-medium">{{ $p->full_name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-500">{{ $p->rank_type_label }}</td>
                        <td class="px-4 py-2 text-xs text-gray-400">{{ $p->personnel_number ?? '—' }}</td>
                        <td class="px-4 py-2 text-center">
                            <select
                                wire:change="updateStatus({{ $p->id }}, $event.target.value, '{{ addslashes($statuses[$p->id]['notes'] ?? '') }}')"
                                class="text-xs border rounded px-2 py-1 dark:bg-gray-800 dark:border-gray-600
                                    {{ match($currentStatus) {
                                        'present'  => 'text-green-700 border-green-400',
                                        'mission'  => 'text-blue-700 border-blue-400',
                                        'leave'    => 'text-indigo-700 border-indigo-400',
                                        'medical'  => 'text-yellow-700 border-yellow-400',
                                        'absent'   => 'text-red-700 border-red-400',
                                        'arrested' => 'text-red-800 border-red-600',
                                        'course'   => 'text-purple-700 border-purple-400',
                                        default    => '',
                                    } }}">
                                <option value="present"  {{ $currentStatus==='present'  ?'selected':'' }}>✅ حاضر</option>
                                <option value="mission"  {{ $currentStatus==='mission'  ?'selected':'' }}>📋 مأمور</option>
                                <option value="leave"    {{ $currentStatus==='leave'    ?'selected':'' }}>🏖️ مرخصی</option>
                                <option value="medical"  {{ $currentStatus==='medical'  ?'selected':'' }}>🏥 بهداری</option>
                                <option value="absent"   {{ $currentStatus==='absent'   ?'selected':'' }}>⚠️ غیبت</option>
                                <option value="arrested" {{ $currentStatus==='arrested' ?'selected':'' }}>🔒 بازداشت</option>
                                <option value="course"   {{ $currentStatus==='course'   ?'selected':'' }}>📚 دوره</option>
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <input
                                type="text"
                                placeholder="توضیحات..."
                                value="{{ $statuses[$p->id]['notes'] ?? '' }}"
                                wire:change="updateStatus({{ $p->id }}, '{{ $currentStatus }}', $event.target.value)"
                                class="text-xs border rounded px-2 py-1 w-full dark:bg-gray-800 dark:border-gray-600"
                            />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="text-center text-sm text-gray-400 py-4">
        برای تغییر وضعیت، تاریخ امروز را انتخاب کنید
    </div>
    @endif

</div>

<style>
@media print {
    nav, aside, header, [data-filament-header], .fi-topbar, .print\:hidden { display: none !important; }
    body { direction: rtl; }
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th, td { border: 1px solid #333 !important; padding: 3px 5px; }
}
</style>
</x-filament-panels::page>
