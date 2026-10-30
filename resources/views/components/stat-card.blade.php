@props(['label', 'value', 'icon', 'color' => 'blue'])

@php
    $colorClasses = [
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400',
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400',
        'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400',
    ];
@endphp

<div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
    <div @class(['flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl', $colorClasses[$color] ?? $colorClasses['blue']])>
        @switch($icon)
            @case('users')
                <x-heroicon-o-users class="h-6 w-6"/>
                @break
            @case('calendar-days')
                <x-heroicon-o-calendar-days class="h-6 w-6"/>
                @break
            @case('clock')
                <x-heroicon-o-clock class="h-6 w-6"/>
                @break
            @case('newspaper')
                <x-heroicon-o-newspaper class="h-6 w-6"/>
                @break
            @default
                <x-heroicon-o-squares-2x2 class="h-6 w-6"/>
        @endswitch
    </div>
    <div class="min-w-0">
        <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($value) }}</p>
    </div>
</div>
