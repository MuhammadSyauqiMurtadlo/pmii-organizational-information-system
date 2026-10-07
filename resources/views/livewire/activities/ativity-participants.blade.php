<div>
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Peserta Kegiatan</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $participants->count() }} terdaftar</p>
        </div>
        @can('update', $activity)
            <button wire:click="$toggle('showAddForm')"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3.5 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                <x-heroicon-o-plus class="h-4 w-4" /> Tambah Peserta
            </button>
        @endcan
    </div>

    @if ($showAddForm)
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
            <div class="flex gap-3">
                <div class="flex-1">
                    <input wire:model.live.debounce.400ms="searchMember" type="text"
                        placeholder="Cari nama anggota..."
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3.5 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @if ($availableMembers->count() > 0)
                        <div
                            class="mt-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
                            @foreach ($availableMembers as $m)
                                <button wire:click="$set('selectedMember', {{ $m->id }})"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors text-left
                                {{ $selectedMember == $m->id ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                                    <img src="{{ $m->user->avatar_url }}"
                                        class="h-7 w-7 rounded-full object-cover flex-shrink-0">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $m->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $m->rayon->name }}</p>
                                    </div>
                                    @if ($selectedMember == $m->id)
                                        <x-heroicon-o-check class="h-4 w-4 text-emerald-600 ml-auto" />
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endif
                    @error('selectedMember')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button wire:click="addParticipant"
                    class="self-start inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                    Tambah
                </button>
            </div>
        </div>
    @endif

    {{-- Participants Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Anggota
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Kehadiran</th>
                    @can('update', $activity)
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi
                        </th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                @forelse($participants as $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $p->member->user->avatar_url }}" class="h-7 w-7 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">
                                        {{ $p->member->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $p->member->rayon->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @can('update', $activity)
                                <select wire:change="updateAttendance({{ $p->id }}, $event.target.value)"
                                    class="text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 py-1 px-2 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                    <option value="registered" {{ $p->attendance === 'registered' ? 'selected' : '' }}>
                                        Terdaftar</option>
                                    <option value="attended" {{ $p->attendance === 'attended' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="absent" {{ $p->attendance === 'absent' ? 'selected' : '' }}>Tidak
                                        Hadir</option>
                                </select>
                            @else
                                <span class="text-xs text-gray-500">{{ ucfirst($p->attendance) }}</span>
                            @endcan
                        </td>
                        @can('update', $activity)
                            <td class="px-4 py-3 text-right">
                                <button wire:click="removeParticipant({{ $p->id }})"
                                    wire:confirm="Hapus peserta ini?"
                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                    <x-heroicon-o-x-mark class="h-4 w-4" />
                                </button>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">Belum ada peserta
                            terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
