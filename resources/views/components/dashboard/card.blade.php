@props(['title', 'value', 'color' => 'blue','link'=>'employee'])

@php
    $colorClasses = [
        'blue' => 'bg-blue-100 text-blue-800',
        'green' => 'bg-green-100 text-green-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red' => 'bg-red-100 text-red-800',
    ];
@endphp

<div class="{{ $colorClasses[$color] ?? $colorClasses['blue'] }} p-4 rounded-2xl shadow-md">
    <h2 class="text-sm font-semibold uppercase"><a href="{{ $link }}">{{ $title }}</a></h2>
    <p class="text-3xl font-bold mt-1">{{ $value }}</p>
</div>
