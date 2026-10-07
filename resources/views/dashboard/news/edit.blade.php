<x-layouts.dashboard>
    <x-slot:title>Edit Berita</x-slot>
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <a href="{{ route('dashboard.news.index') }}" wire:navigate
                class="hover:text-gray-900 dark:hover:text-white">Berita</a>
            <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
            <span class="text-gray-900 dark:text-white truncate max-w-xs">{{ $news->title }}</span>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Berita</h1>
    </div>
    <livewire:news.news-form :news="$news" />
</x-layouts.dashboard>
