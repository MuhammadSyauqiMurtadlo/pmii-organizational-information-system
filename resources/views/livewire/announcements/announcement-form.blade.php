<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul <span
                                class="text-red-500">*</span></label>
                        <input wire:model="title" type="text"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('title') border-red-400 @enderror">
                        @error('title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Isi Pengumuman
                            <span class="text-red-500">*</span></label>
                        <textarea wire:model="content" rows="8"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none @error('content') border-red-400 @enderror"></textarea>
                        @error('content')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Lampiran</label>
                        <input wire:model="attachment" type="file" accept=".pdf,.doc,.docx,.jpg,.png"
                            class="w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        @error('attachment')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Pengaturan</h3>
                <div class="space-y-3">
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Prioritas</label>
                        <select wire:model="priority"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="low">Rendah</option>
                            <option value="normal">Normal</option>
                            <option value="high">Penting</option>
                            <option value="urgent">Mendesak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Target
                            Penerima</label>
                        <select wire:model.live="target_scope"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="all">Semua</option>
                            <option value="komisariat">Komisariat</option>
                            <option value="rayon">Rayon Tertentu</option>
                        </select>
                    </div>
                    @if ($target_scope === 'rayon')
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pilih Rayon
                                <span class="text-red-500">*</span></label>
                            <select wire:model="target_rayon_id"
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('target_rayon_id') border-red-400 @enderror">
                                <option value="">Pilih Rayon</option>
                                @foreach ($rayons as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            @error('target_rayon_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kadaluarsa</label>
                        <input wire:model="expires_at" type="datetime-local"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('expires_at')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-1">
                        <input wire:model="is_pinned" type="checkbox"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs text-gray-700 dark:text-gray-300">Sematkan di atas</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <button wire:click="save" wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                    <span wire:loading.remove>{{ $isEdit ? 'Simpan' : 'Kirim Pengumuman' }}</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
                <a href="{{ route('dashboard.announcements.index') }}" wire:navigate
                    class="w-full inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</div>
