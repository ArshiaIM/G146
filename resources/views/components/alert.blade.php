@props(['type' => 'info', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-100 text-green-700 border-green-300',
        'error' => 'bg-red-100 text-red-700 border-red-300',
        'info' => 'bg-blue-100 text-blue-700 border-blue-300',
    ];
@endphp

<div class="p-3 border rounded-lg {{ $colors[$type] ?? $colors['info'] }}">
    {{ $message }}
</div>
