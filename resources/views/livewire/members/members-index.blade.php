{{-- member-index.blade.php sudah ada di views.blade.php, ini versi final yang sudah diperbaiki --}}
<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Anggota</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $members->total() }} anggota terdaftar</p>
        </div>
        @can('create', App\Models\Member::class)
            <a href="{{ route('dashboard.members.create') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                <x-heroicon-o-plus class="h-4 w-4" /> Tambah Anggota
            </a>
        @endcan
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari nama, NIM, email..."
                    class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAdminKomisariat())
                <select wire:model.live="rayonFilter"
                    class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Rayon</option>
                    @foreach ($rayons as $rayon)
                        <option value="{{ $rayon->id }}">{{ $rayon->name }}</option>
                    @endforeach
                </select>
            @endif
            <select wire:model.live="levelFilter"
                class="text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2 px-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Level</option>
                <option value="kader">Kader</option>
                <option value="anggota_muda">Anggota Muda</option>
                <option value="anggota">Anggota</option>
                <option value="anggota_senior">Anggota Senior</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Anggota</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            NIM</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Rayon</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Level</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}"
                                        class="h-9 w-9 rounded-full object-cover flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $member->user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $member->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-mono text-xs">
                                {{ $member->user->nim ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300 text-sm">{{ $member->rayon->name }}
                            </td>
                            <td class="px-6 py-4">
                                @php $badge = $member->level_badge_color; @endphp
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $badge === 'purple' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-300' : '' }}
                                {{ $badge === 'green' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : '' }}
                                {{ $badge === 'blue' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : '' }}
                                {{ $badge === 'gray' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $member->level)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $member->user->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ ucfirst($member->user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('dashboard.members.show', $member) }}" wire:navigate
                                        class="text-gray-400 hover:text-blue-600 transition-colors" title="Detail">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    @can('update', $member)
                                        <a href="{{ route('dashboard.members.edit', $member) }}" wire:navigate
                                            class="text-gray-400 hover:text-emerald-600 transition-colors" title="Edit">
                                            <x-heroicon-o-pencil-square class="h-4 w-4" />
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-heroicon-o-users class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3" />
                                <p class="text-sm text-gray-500">Belum ada anggota ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($members->hasPages())
            <div class="border-t border-gray-100 dark:border-gray-800 px-6 py-4">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
