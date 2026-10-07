<x-layouts.dashboard>
    <x-slot:title>Detail Anggota</x-slot>

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('dashboard.members.index') }}" wire:navigate
            class="hover:text-gray-900 dark:hover:text-white transition-colors">Anggota</a>
        <x-heroicon-o-chevron-right class="h-3.5 w-3.5" />
        <span class="text-gray-900 dark:text-white truncate">{{ $member->user->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profile Card --}}
        <div class="lg:col-span-1 space-y-5">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center">
                <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}"
                    class="mx-auto h-24 w-24 rounded-full object-cover ring-4 ring-emerald-100 dark:ring-emerald-900/50 mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $member->user->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $member->user->email }}</p>
                @if ($member->user->nim)
                    <p class="text-xs font-mono text-gray-400 mt-1">NIM: {{ $member->user->nim }}</p>
                @endif

                <div class="mt-4 flex items-center justify-center gap-2">
                    {{-- Level badge --}}
                    <span
                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                        {{ $member->level_badge_color === 'purple' ? 'bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-300' : '' }}
                        {{ $member->level_badge_color === 'green' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : '' }}
                        {{ $member->level_badge_color === 'blue' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : '' }}
                        {{ $member->level_badge_color === 'gray' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $member->level)) }}
                    </span>
                    {{-- Status badge --}}
                    <span
                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                        {{ $member->user->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                        {{ ucfirst($member->user->status) }}
                    </span>
                </div>

                @can('update', $member)
                    <div class="mt-5">
                        <a href="{{ route('dashboard.members.edit', $member) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit Profil
                        </a>
                    </div>
                @endcan
            </div>

            {{-- Info Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Informasi Keanggotaan</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Rayon</dt>
                        <dd class="font-medium text-gray-900 dark:text-white text-right">{{ $member->rayon->name }}
                        </dd>
                    </div>
                    @if ($member->member_number)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">No. Anggota</dt>
                            <dd class="font-mono text-xs text-gray-700 dark:text-gray-300">{{ $member->member_number }}
                            </dd>
                        </div>
                    @endif
                    @if ($member->position)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Jabatan</dt>
                            <dd class="font-medium text-gray-900 dark:text-white text-right">{{ $member->position }}
                            </dd>
                        </div>
                    @endif
                    @if ($member->generation)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Angkatan</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ $member->generation }}</dd>
                        </div>
                    @endif
                    @if ($member->joined_date)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Bergabung</dt>
                            <dd class="text-gray-700 dark:text-gray-300">
                                {{ $member->joined_date->translatedFormat('d F Y') }}</dd>
                        </div>
                    @endif
                    @if ($member->user->phone)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">HP</dt>
                            <dd class="text-gray-700 dark:text-gray-300">{{ $member->user->phone }}</dd>
                        </div>
                    @endif
                    @if ($member->user->student_faculty)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Fakultas</dt>
                            <dd class="text-gray-700 dark:text-gray-300 text-right max-w-[60%]">
                                {{ $member->user->student_faculty }}</dd>
                        </div>
                    @endif
                    @if ($member->user->student_major)
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Prodi</dt>
                            <dd class="text-gray-700 dark:text-gray-300 text-right max-w-[60%]">
                                {{ $member->user->student_major }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Kaderisasi Tracker --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Rekam Jejak Kaderisasi</h3>
                <livewire:members.kaderisasi-tracker :member="$member" />
            </div>

            {{-- Kegiatan Diikuti --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">
                    Kegiatan Diikuti
                    <span class="ml-2 text-xs font-normal text-gray-400">({{ $member->activities->count() }})</span>
                </h3>
                @forelse($member->activities->take(5) as $activity)
                    <div
                        class="flex items-center gap-3 py-2.5 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-800' : '' }}">
                        <div
                            class="flex-shrink-0 flex flex-col items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800 w-10 h-10">
                            <span
                                class="text-xs font-bold text-gray-600 dark:text-gray-400 leading-none">{{ $activity->start_date->format('d') }}</span>
                            <span
                                class="text-[10px] text-gray-400 uppercase">{{ $activity->start_date->translatedFormat('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $activity->title }}</p>
                            <p class="text-xs text-gray-400">
                                Kehadiran:
                                <span
                                    class="font-medium
                                {{ $activity->pivot->attendance === 'attended' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                                {{ $activity->pivot->attendance === 'absent' ? 'text-red-500' : '' }}
                                {{ $activity->pivot->attendance === 'registered' ? 'text-blue-500' : '' }}">
                                    {{ ucfirst($activity->pivot->attendance) }}
                                </span>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-4 text-center">Belum ada kegiatan yang diikuti.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.dashboard>
