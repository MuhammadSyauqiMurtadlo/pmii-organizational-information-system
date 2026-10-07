<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Berita</h2>
            <p class="text-sm text-gray-500">{{ $newsList->total() }} berita</p>
        </div>
        <a href="{{ route('dashboard.news.create') }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
            <x-heroicon-o-plus class="h-4 w-4" /> Tulis Berita
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari judul..."
                    class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select wire:model.live="status"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="published">Terbit</option>
                <option value="archived">Arsip</option>
            </select>
            <select wire:model.live="category"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                <option value="berita">Berita</option>
                <option value="artikel">Artikel</option>
                <option value="opini">Opini</option>
                <option value="press_release">Press Release</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Judul</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Kategori</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Views</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tanggal</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($newsList as $news)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($news->thumbnail)
                                        <img src="{{ $news->thumbnail_url }}"
                                            class="h-10 w-16 rounded-lg object-cover flex-shrink-0">
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white line-clamp-1">
                                            {{ $news->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $news->author->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $news->category)) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $news->id }})"
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium cursor-pointer
                                        {{ $news->status === 'published' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : '' }}
                                        {{ $news->status === 'draft' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : '' }}
                                        {{ $news->status === 'archived' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}">
                                    {{ ucfirst($news->status) }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ number_format($news->views) }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                {{ $news->published_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('dashboard.news.edit', $news) }}" wire:navigate
                                        class="text-gray-400 hover:text-emerald-600 transition-colors">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button wire:click="deleteNews({{ $news->id }})"
                                        wire:confirm="Yakin ingin menghapus berita ini?"
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-heroicon-o-newspaper
                                    class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3" />
                                <p class="text-sm text-gray-500">Belum ada berita.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($newsList->hasPages())
            <div class="border-t border-gray-100 dark:border-gray-800 px-6 py-4">
                {{ $newsList->links() }}
            </div>
        @endif
    </div>
</div>
