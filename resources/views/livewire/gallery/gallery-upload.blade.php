<div>
    {{-- Upload Form --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Upload Foto</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pilih Foto (maks. 10,
                    5MB/foto) <span class="text-red-500">*</span></label>
                <input wire:model="photos" type="file" multiple accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                @error('photos.*')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                {{-- Preview --}}
                @if (count($photos) > 0)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($photos as $photo)
                            <img src="{{ $photo->temporaryUrl() }}"
                                class="h-16 w-20 rounded-lg object-cover border border-gray-200">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul <span
                            class="text-red-500">*</span></label>
                    <input wire:model="title" type="text"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kegiatan
                        Terkait</label>
                    <select wire:model="activity_id"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tidak ada</option>
                        @foreach ($activities as $act)
                            <option value="{{ $act->id }}">{{ $act->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_featured" type="checkbox"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs text-gray-700 dark:text-gray-300">Featured</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_public" type="checkbox"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs text-gray-700 dark:text-gray-300">Publik</span>
                    </label>
                </div>
                <button wire:click="upload" wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors disabled:opacity-60">
                    <span wire:loading.remove><x-heroicon-o-arrow-up-tray
                            class="h-4 w-4 inline -mt-0.5 mr-1" />Upload</span>
                    <span wire:loading>Mengupload...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex items-center gap-3 mb-4">
        @if (count($rayons) > 0)
            <select wire:model.live="filterRayon"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Rayon</option>
                @foreach ($rayons as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                @endforeach
            </select>
        @endif
        <select wire:model.live="filterActivity"
            class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-2 px-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Kegiatan</option>
            @foreach ($activities as $act)
                <option value="{{ $act->id }}">{{ $act->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- Gallery Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        @forelse($galleries as $item)
            <div class="group relative rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 aspect-square">
                <img src="{{ $item->file_url }}" alt="{{ $item->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-end">
                    <div class="w-full p-2 translate-y-full group-hover:translate-y-0 transition-transform">
                        <p class="text-white text-xs font-medium line-clamp-1">{{ $item->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <button wire:click="toggleFeatured({{ $item->id }})" title="Toggle Featured"
                                class="text-white/80 hover:text-yellow-400 transition-colors">
                                <x-heroicon-o-star
                                    class="h-3.5 w-3.5 {{ $item->is_featured ? 'text-yellow-400' : '' }}" />
                            </button>
                            <button wire:click="togglePublic({{ $item->id }})" title="Toggle Publik"
                                class="text-white/80 hover:text-blue-400 transition-colors">
                                <x-heroicon-o-eye class="h-3.5 w-3.5 {{ $item->is_public ? 'text-blue-400' : '' }}" />
                            </button>
                            <button wire:click="deletePhoto({{ $item->id }})" wire:confirm="Hapus foto ini?"
                                class="ml-auto text-white/80 hover:text-red-400 transition-colors">
                                <x-heroicon-o-trash class="h-3.5 w-3.5" />
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="absolute top-2 left-2 flex gap-1">
                    @if ($item->is_featured)
                        <span
                            class="text-[10px] bg-yellow-400 text-yellow-900 px-1.5 py-0.5 rounded-full font-medium">⭐</span>
                    @endif
                    @if (!$item->is_public)
                        <span
                            class="text-[10px] bg-gray-900/70 text-white px-1.5 py-0.5 rounded-full font-medium">🔒</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <x-heroicon-o-photo class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3" />
                <p class="text-sm text-gray-500">Belum ada foto di galeri.</p>
            </div>
        @endforelse
    </div>

    @if ($galleries->hasPages())
        <div class="mt-6">{{ $galleries->links() }}</div>
    @endif
</div>
