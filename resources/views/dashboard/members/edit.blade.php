<x-layouts.dashboard>
    <x-slot:title>Edit Anggota</x-slot>

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
            <a href="{{ route('dashboard.members.index') }}" wire:navigate
                class="hover:text-gray-900 dark:hover:text-white transition-colors">Anggota</a>
            <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
            <a href="{{ route('dashboard.members.show', $member) }}" wire:navigate
                class="hover:text-gray-900 dark:hover:text-white transition-colors truncate max-w-xs">{{ $member->user->name }}</a>
            <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
            <span class="text-gray-900 dark:text-white">Edit</span>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Data Anggota</h1>
    </div>

    <livewire:members.member-form :member="$member" />
</x-layouts.dashboard>
