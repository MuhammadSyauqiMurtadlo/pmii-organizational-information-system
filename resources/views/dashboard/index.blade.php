<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot>

    {{-- Stats Overview --}}
    <livewire:dashboard.stats-overview />

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        {{-- Recent Activities --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Kegiatan Terbaru</h3>
                @if(auth()->user()->hasAnyPermission(['manage_rayon_activities','manage_komisariat_activities']))
                <a href="{{ route('dashboard.activities.index') }}" wire:navigate
                   class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat semua</a>
                @endif
            </div>
            <livewire:dashboard.recent-activities />
        </div>

        {{-- Announcements --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pengumuman</h3>
                @if(auth()->user()->hasPermissionTo('manage_announcements'))
                <a href="{{ route('dashboard.announcements.index') }}" wire:navigate
                   class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Kelola</a>
                @endif
            </div>
            @forelse($announcements as $ann)
            <div class="flex gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-800' : '' }}">
                <span class="flex-shrink-0 mt-0.5 h-2 w-2 rounded-full
                    {{ $ann->priority === 'urgent' ? 'bg-red-500' : '' }}
                    {{ $ann->priority === 'high'   ? 'bg-orange-500' : '' }}
                    {{ $ann->priority === 'normal' ? 'bg-blue-400' : '' }}
                    {{ $ann->priority === 'low'    ? 'bg-gray-400' : '' }}">
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ann->title }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $ann->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 py-4 text-center">Tidak ada pengumuman aktif.</p>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>