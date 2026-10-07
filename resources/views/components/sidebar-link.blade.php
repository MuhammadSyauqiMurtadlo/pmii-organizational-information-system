@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}" wire:navigate
   @class([
       'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors group',
       'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' => $active,
       'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' => !$active,
   ])>
    <span @class([
        'flex h-5 w-5 items-center justify-center flex-shrink-0',
        'text-emerald-600 dark:text-emerald-400' => $active,
        'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' => !$active,
    ])>
        @switch($icon)
            @case('squares-2x2')     <x-heroicon-o-squares-2x2 class="h-5 w-5"/>     @break
            @case('users')           <x-heroicon-o-users class="h-5 w-5"/>            @break
            @case('user-group')      <x-heroicon-o-user-group class="h-5 w-5"/>       @break
            @case('newspaper')       <x-heroicon-o-newspaper class="h-5 w-5"/>        @break
            @case('calendar-days')   <x-heroicon-o-calendar-days class="h-5 w-5"/>   @break
            @case('photo')           <x-heroicon-o-photo class="h-5 w-5"/>            @break
            @case('megaphone')       <x-heroicon-o-megaphone class="h-5 w-5"/>        @break
            @case('building-library')<x-heroicon-o-building-library class="h-5 w-5"/>@break
            @default                 <x-heroicon-o-square-2-stack class="h-5 w-5"/>
        @endswitch
    </span>
    <span class="truncate">{{ $slot }}</span>
</a>