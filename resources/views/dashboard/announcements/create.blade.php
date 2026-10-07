<x-layouts.dashboard>
    <x-slot:title>Buat Pengumuman</x-slot>
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <a href="{{ route('dashboard.announcements.index') }}" wire:navigate
                class="hover:text-gray-900 dark:hover:text-white">Pengumuman</a>
            <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
            <span class="text-gray-900 dark:text-white">Buat Baru</span>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Buat Pengumuman Baru</h1>
    </div>
    <livewire:announcements.announcement-form />
</x-layouts.dashboard>
