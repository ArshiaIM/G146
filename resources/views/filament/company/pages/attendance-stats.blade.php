<x-filament-widgets::widget>
<div style="direction:rtl;">

    {{-- جدول آماری --}}
    <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;margin-bottom:16px;">
        <div style="background:#1f2937;color:white;padding:8px 16px;font-weight:bold;text-align:center;font-size:13px;">
            جدول آمار نفرات
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:12px;text-align:center;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="color:black;padding:8px;border:1px solid #e5e7eb;text-align:center;">رتبه</th>
                        @foreach(['سازمانی','موجودی','حاضر','مأمور','مرخصی','بهداری','غیبت','بازداشت','دوره'] as $col)
                        <th style="color:black;padding:8px;border:1px solid #e5e7eb;">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ranks = [
                            'officer_a'      => 'افسر الف',
                            'officer_b'      => 'افسر ب',
                            'vazife_officer' => 'افسر وظیفه',
                            'nco'            => 'د.پایور',
                            'vazife_nco'     => 'د.وظیفه',
                            'soldier'        => 'سرباز',
                            'total'          => 'جمع',
                        ];
                        $statusKeys = ['organizational','actual','present','mission','leave','medical','absent','arrested','course'];
                    @endphp

                    @foreach($ranks as $rankKey => $rankLabel)
                    @php
                        $isTotal = $rankKey === 'total';
                        $rowStyle = $isTotal ? 'background:#1f2937;color:white;font-weight:bold;' : '';
                    @endphp
                    <tr style="{{ $rowStyle }}">
                        <td style="padding:8px;border:1px solid #e5e7eb;text-align:right;font-weight:bold;">
                            {{ $rankLabel }}
                        </td>
                        @foreach($statusKeys as $statusKey)
                        @php
                            $val = $isTotal
                                ? ($stats[$statusKey]['total'] ?? 0)
                                : ($stats[$statusKey][$rankKey] ?? 0);

                            $cellStyle = 'padding:8px;border:1px solid #e5e7eb;';
                            if ($statusKey === 'present' && !$isTotal) $cellStyle .= 'color:#15803d;font-weight:bold;';
                            if (in_array($statusKey, ['absent','arrested']) && $val > 0) $cellStyle .= 'color:#dc2626;font-weight:bold;';
                        @endphp
                        <td style="{{ $cellStyle }}">{{ $val }}</td>
                        @endforeach
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

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
        @foreach($absentLabels as $status => $label)
        @php $people = $absentList[$status] ?? []; @endphp
        @if(count($people) > 0)
        <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
            <div style="background:#f3f4f6;padding:6px 12px;font-weight:bold;font-size:12px;display:flex;justify-content:space-between;">
                <span>{{ $label }}</span>
                <span style="background:white;border-radius:999px;padding:0 8px;">{{ count($people) }}</span>
            </div>
            <ul style="margin:0;padding:0;list-style:none;">
                @foreach($people as $person)
                <li style="padding:5px 12px;border-top:1px solid #f3f4f6;font-size:12px;">
                    <span style="color:#9ca3af;font-size:11px;">{{ $person['rank'] }}</span>
                    <strong>{{ $person['name'] }}</strong>
                    @if($person['notes'])
                    <div style="color:#6b7280;font-size:11px;">{{ $person['notes'] }}</div>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @endforeach
    </div>

</div>
</x-filament-widgets::widget>
