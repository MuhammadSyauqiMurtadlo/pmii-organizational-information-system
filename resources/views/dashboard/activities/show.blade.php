<x-layouts.dashboard>
    <x-slot:title>Detail Kegiatan</x-slot>

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('dashboard.activities.index') }}" wire:navigate
            class="hover:text-gray-900 dark:hover:text-white">Kegiatan</a>
        <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
        <span class="text-gray-900 dark:text-white truncate">{{ $activity->title }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Poster + Judul --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                @if ($activity->poster)
                    <div class="aspect-[21/9] overflow-hidden bg-gray-100 dark:bg-gray-800">
                        <img src="{{ asset('storage/' . $activity->poster) }}" alt="{{ $activity->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $activity->title }}</h1>
                        <span
                            class="flex-shrink-0 inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            {{ $activity->status === 'upcoming' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $activity->status === 'ongoing' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $activity->status === 'completed' ? 'bg-gray-100 text-gray-600' : '' }}
                            {{ $activity->status === 'cancelled' ? 'bg-red-50 text-red-600' : '' }}">
                            {{ $activity->status_badge['label'] }}
                        </span>
                    </div>
                    @if ($activity->description)
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ $activity->description }}</p>
                    @endif
                    @if ($activity->objective)
                        <div
                            class="mt-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
                            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-1">Tujuan Kegiatan
                            </p>
                            <p class="text-sm text-emerald-800 dark:text-emerald-300">{{ $activity->objective }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Manajemen Peserta --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <livewire:activities.activity-participants :activity="$activity" />
            </div>

            {{-- Galeri Kegiatan --}}
            @if ($activity->galleries->count() > 0)
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Dokumentasi</h3>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach ($activity->galleries->take(8) as $gallery)
                            <a href="{{ $gallery->file_url }}" target="_blank"
                                class="aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 group">
                                <img src="{{ $gallery->file_url }}" alt="{{ $gallery->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                        @endforeach
                    </div>
                    @if ($activity->galleries->count() > 8)
                        <p class="text-xs text-gray-400 mt-2 text-center">+ {{ $activity->galleries->count() - 8 }}
                            foto lainnya</p>
                    @endif
                </div>
            @endif

            {{-- Laporan Kegiatan --}}
            @if ($activity->report)
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Laporan Kegiatan</h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($activity->report)) !!}
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Informasi Kegiatan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-calendar-days class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" />
                        <div>
                            <p class="text-gray-900 dark:text-white font-medium">
                                {{ $activity->start_date->translatedFormat('d F Y, H:i') }}</p>
                            @if ($activity->start_date->toDateString() !== $activity->end_date->toDateString())
                                <p class="text-gray-500 text-xs">s/d
                                    {{ $activity->end_date->translatedFormat('d F Y, H:i') }}</p>
                            @else
                                <p class="text-gray-500 text-xs">s/d {{ $activity->end_date->format('H:i') }} WIB</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-map-pin class="h-4 w-4 text-gray-400 flex-shrink-0 mt-0.5" />
                        <p class="text-gray-700 dark:text-gray-300">{{ $activity->location }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-tag class="h-4 w-4 text-gray-400 flex-shrink-0" />
                        <p class="text-gray-700 dark:text-gray-300">{{ ucfirst($activity->type) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-building-office class="h-4 w-4 text-gray-400 flex-shrink-0" />
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $activity->rayon?->name ?? 'Tingkat Komisariat' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-users class="h-4 w-4 text-gray-400 flex-shrink-0" />
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $activity->participant_count }} peserta
                            @if ($activity->max_participants)
                                / {{ $activity->max_participants }} maks.
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-user class="h-4 w-4 text-gray-400 flex-shrink-0" />
                        <p class="text-gray-700 dark:text-gray-300">{{ $activity->organizer->name }}</p>
                    </div>
                </dl>
            </div>

            @can('update', $activity)
                <a href="{{ route('dashboard.activities.edit', $activity) }}" wire:navigate
                    class="flex items-center justify-center gap-2 w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit Kegiatan
                </a>
            @endcan
        </div>
    </div>
</x-layouts.dashboard>
