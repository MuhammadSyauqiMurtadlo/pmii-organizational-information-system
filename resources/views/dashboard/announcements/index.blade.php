<x-layouts.dashboard>
    <x-slot:title>Pengumuman</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pengumuman</h2>
        <a href="{{ route('dashboard.announcements.create') }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
            <x-heroicon-o-plus class="h-4 w-4" /> Buat Pengumuman
        </a>
    </div>

    @php
        $announcements = \App\Models\Announcement::forUser(auth()->user())
            ->with('author', 'targetRayon')
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15);
    @endphp

    <div class="space-y-3">
        @forelse($announcements as $ann)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-start gap-3">
                    @if ($ann->is_pinned)
                        <x-heroicon-o-bookmark class="h-5 w-5 text-emerald-500 flex-shrink-0 mt-0.5" />
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $ann->title }}</h3>
                            <span
                                class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $ann->priority === 'urgent' ? 'bg-red-50 text-red-600' : '' }}
                            {{ $ann->priority === 'high' ? 'bg-orange-50 text-orange-600' : '' }}
                            {{ $ann->priority === 'normal' ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ $ann->priority === 'low' ? 'bg-gray-100 text-gray-500' : '' }}">
                                {{ $ann->priority_badge['label'] }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $ann->content }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>{{ $ann->author->name }}</span>
                            <span>·</span>
                            <span>{{ $ann->created_at->diffForHumans() }}</span>
                            <span>·</span>
                            <span>{{ ucfirst($ann->target_scope) }}{{ $ann->targetRayon ? ' - ' . $ann->targetRayon->name : '' }}</span>
                            @if ($ann->expires_at)
                                <span>· Kadaluarsa {{ $ann->expires_at->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('dashboard.announcements.create') }}" wire:navigate
                        class="flex-shrink-0 text-gray-400 hover:text-emerald-600 transition-colors">
                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                    </a>
                </div>
            </div>
        @empty
            <div
                class="py-16 text-center bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
                <x-heroicon-o-megaphone class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3" />
                <p class="text-sm text-gray-500">Belum ada pengumuman.</p>
            </div>
        @endforelse

        @if ($announcements->hasPages())
            <div class="mt-4">{{ $announcements->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>
