<div>
    @forelse($activities as $activity)
        <div
            class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-800' : '' }}">
            <div
                class="flex-shrink-0 flex flex-col items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 w-10 h-10">
                <span
                    class="text-sm font-bold text-emerald-600 dark:text-emerald-400 leading-none">{{ $activity->start_date->format('d') }}</span>
                <span
                    class="text-[10px] text-emerald-500/70 uppercase">{{ $activity->start_date->translatedFormat('M') }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $activity->title }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-gray-400">{{ $activity->rayon?->name ?? 'Komisariat' }}</span>
                    <span
                        class="inline-flex rounded-full px-1.5 py-0.5 text-[10px] font-medium
                    {{ $activity->status === 'upcoming' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                    {{ $activity->status === 'ongoing' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : '' }}
                    {{ $activity->status === 'completed' ? 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' : '' }}
                    {{ $activity->status === 'cancelled' ? 'bg-red-50 text-red-500 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                        {{ $activity->status_badge['label'] }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 text-center py-6">Belum ada kegiatan.</p>
    @endforelse
</div>
