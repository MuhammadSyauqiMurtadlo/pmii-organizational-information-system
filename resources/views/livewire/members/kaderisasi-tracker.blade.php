<div>
    {{-- Progress Bar --}}
    <div class="grid grid-cols-4 gap-3 mb-6">
        @foreach (['MAPABA' => 'Masa Penerimaan', 'PKD' => 'Kader Dasar', 'PKL' => 'Kader Lanjut', 'MKDK' => 'Kesetiaan'] as $key => $label)
            <div
                class="rounded-xl border p-3 text-center transition-colors
            {{ $progress[$key] ? 'border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900' }}">
                <div class="text-lg mb-1">{{ $progress[$key] ? '✅' : '⭕' }}</div>
                <p
                    class="text-xs font-bold {{ $progress[$key] ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-500' }}">
                    {{ $key }}</p>
                <p class="text-[10px] text-gray-400 leading-tight mt-0.5">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- Add Button --}}
    @can('update', $member)
        <div class="mb-4">
            <button wire:click="$toggle('showForm')"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                <x-heroicon-o-plus class="h-4 w-4" />
                {{ $showForm ? 'Tutup Form' : 'Tambah Record' }}
            </button>
        </div>

        {{-- Form --}}
        @if ($showForm)
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 mb-5">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Record Kaderisasi Baru</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis <span
                                class="text-red-500">*</span></label>
                        <select wire:model="type"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="MAPABA">MAPABA</option>
                            <option value="PKD">PKD</option>
                            <option value="PKL">PKL</option>
                            <option value="MKDK">MKDK</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status <span
                                class="text-red-500">*</span></label>
                        <select wire:model="status"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="pending">Pending</option>
                            <option value="lulus">Lulus</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama
                            Kegiatan</label>
                        <input wire:model="event_name" type="text"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input wire:model="event_date" type="date"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('event_date') border-red-400 @enderror">
                        @error('event_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Lokasi</label>
                        <input wire:model="location" type="text"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Fasilitator</label>
                        <input wire:model="facilitator" type="text"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">No.
                            Sertifikat</label>
                        <input wire:model="certificate_number" type="text"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">File
                            Sertifikat</label>
                        <input wire:model="certificate_file" type="file" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                </div>
                <div class="flex gap-3 mt-4">
                    <button wire:click="saveRecord" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                        Simpan Record
                    </button>
                    <button wire:click="$set('showForm', false)"
                        class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        @endif
    @endcan

    {{-- Records List --}}
    <div class="space-y-3">
        @forelse($records as $record)
            <div
                class="flex items-start gap-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                <div class="flex-shrink-0 text-xl">
                    {{ $record->status === 'lulus' ? '✅' : ($record->status === 'tidak_lulus' ? '❌' : '🕐') }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $record->type }}</span>
                        @if ($record->event_name)
                            <span class="text-xs text-gray-500">— {{ $record->event_name }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $record->event_date->translatedFormat('d F Y') }}
                        @if ($record->location)
                            · {{ $record->location }}
                        @endif
                    </p>
                    @if ($record->certificate_file)
                        <a href="{{ asset('storage/' . $record->certificate_file) }}" target="_blank"
                            class="mt-1 inline-flex items-center gap-1 text-xs text-emerald-600 hover:underline">
                            <x-heroicon-o-document class="h-3 w-3" /> Lihat Sertifikat
                        </a>
                    @endif
                </div>
                @can('update', $member)
                    <button wire:click="deleteRecord({{ $record->id }})" wire:confirm="Hapus record kaderisasi ini?"
                        class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                        <x-heroicon-o-trash class="h-4 w-4" />
                    </button>
                @endcan
            </div>
        @empty
            <div class="py-10 text-center">
                <p class="text-sm text-gray-400">Belum ada record kaderisasi.</p>
            </div>
        @endforelse
    </div>
</div>
