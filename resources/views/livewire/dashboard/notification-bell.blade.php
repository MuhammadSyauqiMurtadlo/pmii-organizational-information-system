<div class="relative" x-data>
    <button wire:click="toggle"
        class="relative p-2 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        <x-heroicon-o-bell class="h-5 w-5" />
        @if ($unreadCount > 0)
            <span
                class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if ($isOpen)
        {{-- Backdrop click to close --}}
        <div wire:click="toggle" class="fixed inset-0 z-10"></div>

        <div
            class="absolute right-0 top-full mt-2 z-20 w-80 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Pengumuman</p>
                <button wire:click="toggle" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>

            <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($announcements as $ann)
                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-start gap-2">
                            <span
                                class="flex-shrink-0 mt-1.5 h-2 w-2 rounded-full
                        {{ $ann->priority === 'urgent' ? 'bg-red-500' : '' }}
                        {{ $ann->priority === 'high' ? 'bg-orange-500' : '' }}
                        {{ $ann->priority === 'normal' ? 'bg-blue-400' : '' }}
                        {{ $ann->priority === 'low' ? 'bg-gray-400' : '' }}">
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white leading-snug">
                                    {{ $ann->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">
                                    {{ $ann->content }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $ann->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <x-heroicon-o-bell-slash class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-700 mb-2" />
                        <p class="text-sm text-gray-400">Tidak ada pengumuman.</p>
                    </div>
                @endforelse
            </div>

            @if (auth()->user()->hasPermissionTo('manage_announcements'))
                <div class="border-t border-gray-100 dark:border-gray-800 px-4 py-2.5">
                    <a href="{{ route('dashboard.announcements.index') }}" wire:navigate wire:click="toggle"
                        class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                        Kelola pengumuman →
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
