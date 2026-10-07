<div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Total Anggota"       :value="$stats['total_members'] ?? 0"       icon="users"         color="emerald"/>
        <x-stat-card label="Total Kegiatan"      :value="$stats['total_activities'] ?? 0"    icon="calendar-days" color="blue"/>
        <x-stat-card label="Kegiatan Mendatang"  :value="$stats['upcoming_activities'] ?? 0" icon="clock"         color="amber"/>
        <x-stat-card label="Berita Terbit"       :value="$stats['news_published'] ?? 0"      icon="newspaper"     color="purple"/>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Kaderisasi Progress --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Progress Kaderisasi</h3>
            <div class="space-y-4">
                @foreach(['MAPABA' => 'Masa Penerimaan Anggota Baru', 'PKD' => 'Pelatihan Kader Dasar', 'PKL' => 'Pelatihan Kader Lanjut'] as $key => $label)
                @php
                    $count = $stats['kaderisasi_stats'][$key] ?? 0;
                    $total = max($stats['total_members'] ?? 1, 1);
                    $pct   = round(($count / $total) * 100);
                @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $key }} <span class="text-gray-400 font-normal">— {{ $label }}</span></span>
                        <span class="text-gray-500">{{ $count }} / {{ $total }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-2 rounded-full bg-emerald-500 transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Anggota per Rayon --}}
        @isset($stats['members_by_rayon'])
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Anggota per Rayon</h3>
            <div class="space-y-3">
                @php $max = max(collect($stats['members_by_rayon'])->pluck('count')->max(), 1); @endphp
                @foreach($stats['members_by_rayon'] as $rayon)
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-medium text-gray-700 dark:text-gray-300 truncate">{{ $rayon['name'] }}</span>
                        <span class="text-gray-500 ml-2 flex-shrink-0">{{ $rayon['count'] }}</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ ($rayon['count'] / $max) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endisset
    </div>
</div>
