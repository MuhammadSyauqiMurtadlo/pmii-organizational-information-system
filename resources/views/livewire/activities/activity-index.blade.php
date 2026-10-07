<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kegiatan</h2>
            <p class="text-sm text-gray-500">{{ $activities->total() }} kegiatan</p>
        </div>
        <a href="{{ route('dashboard.activities.create') }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
            <x-heroicon-o-plus class="h-4 w-4" /> Buat Kegiatan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari kegiatan..."
                    class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select wire:model.live="typeFilter"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Tipe</option>
                @foreach (['kajian', 'pelatihan', 'rapat', 'aksi', 'sosial', 'kaderisasi', 'lainnya'] as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="upcoming">Akan Datang</option>
                <option value="ongoing">Berlangsung</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($activities as $activity)
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 hover:shadow-sm transition-shadow">
                <div class="flex items-start gap-4">
                    <div
                        class="flex-shrink-0 flex flex-col items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20 w-12 h-12">
                        <span
                            class="text-lg font-bold text-emerald-600 dark:text-emerald-400 leading-none">{{ $activity->start_date->format('d') }}</span>
                        <span
                            class="text-[10px] text-emerald-500/70 uppercase">{{ $activity->start_date->translatedFormat('M') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $activity->title }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-gray-500">
                                    <span>{{ $activity->rayon?->name ?? 'Komisariat' }}</span>
                                    <span>·</span>
                                    <span>{{ $activity->location }}</span>
                                    <span>·</span>
                                    <span>{{ $activity->participant_count }} peserta</span>
                                </div>
                            </div>
                            <span
                                class="flex-shrink-0 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $activity->status === 'upcoming' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                            {{ $activity->status === 'ongoing' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : '' }}
                            {{ $activity->status === 'completed' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}
                            {{ $activity->status === 'cancelled' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                {{ $activity->status_badge['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('dashboard.activities.show', $activity) }}" wire:navigate
                            class="text-gray-400 hover:text-blue-600 transition-colors" title="Detail">
                            <x-heroicon-o-eye class="h-4 w-4" />
                        </a>
                        <a href="{{ route('dashboard.activities.edit', $activity) }}" wire:navigate
                            class="text-gray-400 hover:text-emerald-600 transition-colors" title="Edit">
                            <x-heroicon-o-pencil-square class="h-4 w-4" />
                        </a>
                        <button wire:click="deleteActivity({{ $activity->id }})"
                            wire:confirm="Yakin ingin menghapus kegiatan ini?"
                            class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                            <x-heroicon-o-trash class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="py-16 text-center bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
                <x-heroicon-o-calendar-days class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3" />
                <p class="text-sm text-gray-500">Belum ada kegiatan.</p>
            </div>
        @endforelse
    </div>

    @if ($activities->hasPages())
        <div class="mt-4">{{ $activities->links() }}</div>
    @endif
</div>
