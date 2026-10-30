@extends('layouts.public')
@section('title', 'Beranda - PMII')
@section('meta-description', 'Website resmi PMII - Pergerakan Mahasiswa Islam Indonesia, organisasi mahasiswa Islam terbesar berbasis Ahlussunnah Wal Jamaah.')

@section('content')

{{-- ════════════ HERO SECTION ════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-yellow-300 via-amber-200 to-sky-200 pt-10 sm:pt-14">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-yellow-100/70 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-blue-500/15 blur-3xl"></div>
    </div>
    {{-- Grid pattern --}}
    <div class="absolute inset-0 opacity-5"
         style="background-image: linear-gradient(rgba(30,64,175,.08) 1px, transparent 1px),
                                  linear-gradient(90deg, rgba(30,64,175,.08) 1px, transparent 1px);
                background-size: 48px 48px;"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 sm:py-20 lg:py-24">
        <div class="flex flex-col lg:flex-row items-stretch gap-10 lg:gap-12">
            {{-- Text --}}
            <div class="flex flex-1 flex-col justify-center text-center lg:text-left">
                <div class="inline-flex items-center gap-2 self-center rounded-full border border-blue-900/10 bg-white/55 px-4 py-1.5 text-xs font-semibold text-blue-900 shadow-sm backdrop-blur-sm lg:self-start">
                    <span class="h-2 w-2 rounded-full bg-yellow-500 ring-4 ring-yellow-500/15"></span>
                    Pergerakan Mahasiswa Islam Indonesia
                </div>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight text-blue-950 sm:text-5xl lg:text-6xl">
                    Bersama
                    <span class="relative inline-block rounded-xl bg-white/45 px-3 text-transparent ring-1 ring-white/50">
                        <span class="bg-gradient-to-r from-blue-900 via-blue-700 to-sky-700 bg-clip-text text-transparent">PMII</span>
                    </span>
                    <span class="mt-2 block">Bergerak Menuju Perubahan</span>
                </h1>
                <p class="mx-auto mt-6 max-w-xl text-base leading-relaxed text-blue-950/75 sm:text-lg lg:mx-0">
                    Organisasi mahasiswa Islam terbesar yang berlandaskan Ahlussunnah Wal Jama'ah,
                    berkomitmen mencetak kader intelektual yang berakhlak mulia.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row lg:justify-start">
                    <a href="{{ route('public.about') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-950 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-800 focus-visible:ring-offset-2 focus-visible:ring-offset-amber-200">
                        Tentang PMII <x-heroicon-o-arrow-right class="h-4 w-4"/>
                    </a>
                    <a href="{{ route('public.activities.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-900/15 bg-white/65 px-6 py-3.5 text-sm font-semibold text-blue-950 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-800 focus-visible:ring-offset-2 focus-visible:ring-offset-amber-200">
                        Lihat Kegiatan
                    </a>
                </div>
                <div class="mt-8 flex items-center justify-center gap-3 text-xs font-medium text-blue-950/65 lg:justify-start">
                    <span class="flex -space-x-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-amber-200 bg-blue-950 text-[10px] font-bold text-yellow-300">P</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-amber-200 bg-yellow-400 text-[10px] font-bold text-blue-950">M</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border-2 border-amber-200 bg-sky-700 text-[10px] font-bold text-white">II</span>
                    </span>
                    <span>Bergerak bersama, bertumbuh bersama.</span>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="relative flex flex-shrink-0 flex-col justify-center overflow-hidden rounded-[2rem] border border-blue-900/10 bg-blue-950 p-6 shadow-2xl shadow-blue-950/20 sm:p-8 lg:w-[43%]">
                <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full border-[32px] border-yellow-400/15"></div>
                <div class="pointer-events-none absolute -bottom-24 -left-16 h-56 w-56 rounded-full bg-sky-400/10 blur-2xl"></div>
                <div class="relative mb-6 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-yellow-300">Keluarga Besar PMII</p>
                        <p class="mt-2 text-sm text-blue-100/75">Tumbuh dalam ilmu, bergerak untuk sesama.</p>
                    </div>
                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-yellow-400 text-lg font-black text-blue-950 shadow-lg shadow-yellow-400/20">PM</span>
                </div>
                <div class="relative grid grid-cols-2 gap-3">
                @foreach([
                    ['value' => $stats['total_members']              ?? 0, 'label' => 'Anggota Aktif',    'icon' => '👥'],
                    ['value' => $stats['total_rayons']               ?? 0, 'label' => 'Rayon',            'icon' => '🏛️'],
                    ['value' => $stats['total_activities']           ?? 0, 'label' => 'Kegiatan',         'icon' => '📅'],
                    ['value' => $stats['kaderisasi_stats']['MAPABA'] ?? 0, 'label' => 'Lulus Kaderisasi', 'icon' => '🎓'],
                ] as $stat)
                <div class="rounded-2xl border border-white/10 {{ $loop->first || $loop->last ? 'bg-yellow-400 text-blue-950' : 'bg-white/[0.08] text-white' }} p-4 transition-transform duration-200 hover:-translate-y-1 sm:p-5">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-xl">{{ $stat['icon'] }}</span>
                        <span class="h-1.5 w-8 rounded-full {{ $loop->first || $loop->last ? 'bg-blue-900/25' : 'bg-yellow-400' }}"></span>
                    </div>
                    <div class="text-3xl font-bold leading-none">{{ number_format($stat['value']) }}</div>
                    <div class="mt-2 text-xs font-semibold {{ $loop->first || $loop->last ? 'text-blue-950/70' : 'text-blue-100/75' }}">{{ $stat['label'] }}</div>
                </div>
                @endforeach
                </div>
                <div class="relative mt-5 flex items-center justify-between border-t border-white/10 pt-4 text-xs text-blue-100/65">
                    <span>Pergerakan Mahasiswa Islam Indonesia</span>
                    <span class="font-semibold text-yellow-300">PMII</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave bottom --}}
    <div class="relative -mb-px">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" class="fill-white dark:fill-gray-950"/>
        </svg>
    </div>
</section>


{{-- ════════════ LATEST NEWS ════════════ --}}
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-700 dark:text-blue-300 mb-2">Terkini <span class="ml-2 inline-block h-1.5 w-8 rounded-full bg-yellow-400 align-middle"></span></p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Berita & Artikel</h2>
            </div>
            <a href="{{ route('public.news.index') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition-colors">
                Lihat semua <x-heroicon-o-arrow-right class="h-4 w-4"/>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestNews as $item)
            <article class="group rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:shadow-xl hover:shadow-gray-100/50 dark:hover:shadow-black/20 transition-all duration-300 hover:-translate-y-1">
                <a href="{{ route('public.news.show', $item->slug) }}">
                    <div class="aspect-[16/9] overflow-hidden bg-gray-100 dark:bg-gray-800">
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    </div>
                </a>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex rounded-full bg-yellow-100 dark:bg-yellow-400/15 px-2.5 py-0.5 text-xs font-semibold text-blue-900 dark:text-yellow-200">
                            {{ ucfirst($item->category) }}
                        </span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $item->published_at?->diffForHumans() }}
                        </span>
                    </div>
                    <a href="{{ route('public.news.show', $item->slug) }}">
                        <h3 class="font-semibold text-gray-900 dark:text-white leading-snug mb-2 line-clamp-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            {{ $item->title }}
                        </h3>
                    </a>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                        {{-- Fix: pakai helper str() bukan Str:: facade langsung di view --}}
                        {{ $item->excerpt ?? str($item->content)->stripTags()->limit(100) }}
                    </p>
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <img src="{{ $item->author->avatar_url }}" alt="{{ $item->author->name }}"
                             class="h-6 w-6 rounded-full object-cover">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->author->name }}</span>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-3 py-12 text-center">
                <x-heroicon-o-newspaper class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3"/>
                <p class="text-sm text-gray-400">Belum ada berita tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- ════════════ UPCOMING ACTIVITIES ════════════ --}}
<section class="py-20 bg-gray-50 dark:bg-gray-900/50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-700 dark:text-blue-300 mb-2">Agenda <span class="ml-2 inline-block h-1.5 w-8 rounded-full bg-yellow-400 align-middle"></span></p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Kegiatan Mendatang</h2>
            </div>
            <a href="{{ route('public.activities.index') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition-colors">
                Lihat semua <x-heroicon-o-arrow-right class="h-4 w-4"/>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($upcomingActivities as $activity)
            <a href="{{ route('public.activities.show', $activity->slug) }}"
               class="group flex gap-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 hover:shadow-lg hover:border-yellow-300 dark:hover:border-yellow-500/50 transition-all duration-200">
                <div class="flex-shrink-0 flex flex-col items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-400/15 border border-yellow-200 dark:border-yellow-400/20 w-16 h-16">
                    <span class="text-xl font-bold text-blue-900 dark:text-yellow-200 leading-none">
                        {{ $activity->start_date->format('d') }}
                    </span>
                    <span class="text-xs font-semibold text-blue-700/70 dark:text-blue-300 uppercase">
                        {{ $activity->start_date->translatedFormat('M') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-1">
                        {{ $activity->title }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 line-clamp-1">
                        {{ $activity->description }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-map-pin class="h-3.5 w-3.5"/>
                            {{ $activity->location }}
                        </span>
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-clock class="h-3.5 w-3.5"/>
                            {{ $activity->start_date->format('H:i') }} WIB
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-2 py-12 text-center">
                <x-heroicon-o-calendar-days class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-700 mb-3"/>
                <p class="text-sm text-gray-400">Belum ada kegiatan mendatang.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- ════════════ RAYON SHOWCASE ════════════ --}}
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-700 dark:text-blue-300 mb-2">Struktur <span class="ml-2 inline-block h-1.5 w-8 rounded-full bg-yellow-400 align-middle"></span></p>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Rayon PMII</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto text-sm leading-relaxed">
                PMII di kampus kami tersebar di berbagai fakultas melalui rayon yang aktif bergerak.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($rayons as $rayon)
            <div class="group text-center rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 p-6 hover:border-yellow-300 dark:hover:border-yellow-500/50 hover:bg-yellow-50 dark:hover:bg-yellow-400/5 transition-all duration-200">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 group-hover:bg-blue-950 group-hover:border-blue-950 transition-all duration-200 shadow-sm">
                    @if($rayon->logo)
                    <img src="{{ asset('storage/' . $rayon->logo) }}" alt="{{ $rayon->name }}" class="h-8 w-8 object-contain">
                    @else
                    <x-heroicon-o-building-library class="h-6 w-6 text-gray-500 group-hover:text-white transition-colors"/>
                    @endif
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                    {{ $rayon->name }}
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 leading-snug line-clamp-2">{{ $rayon->faculty }}</p>
                <div class="mt-3 text-xs font-semibold text-blue-700 dark:text-blue-300">
                    {{ $rayon->members_count }} anggota
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════ COMMUNITY CTA ════════════ --}}
<section class="bg-gradient-to-r from-yellow-300 via-amber-200 to-yellow-100 py-14 dark:from-blue-950 dark:via-blue-900 dark:to-sky-950 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2rem] bg-blue-950 px-6 py-10 text-center shadow-2xl shadow-blue-950/15 sm:px-12 sm:py-12">
            <div class="pointer-events-none absolute -right-14 -top-24 h-64 w-64 rounded-full border-[28px] border-yellow-400/15"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-16 h-64 w-64 rounded-full bg-sky-400/10 blur-2xl"></div>
            <div class="relative mx-auto max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-yellow-300/20 bg-yellow-400/10 px-3 py-1 text-xs font-semibold text-yellow-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-300"></span>
                    Bergerak bersama PMII
                </span>
                <h2 class="mt-5 text-2xl font-bold tracking-tight text-white sm:text-3xl">Masa depan dibangun bersama.</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-blue-100/75 sm:text-base">
                    Kenali gerakan, temukan ruang untuk bertumbuh, dan ambil bagian dalam perubahan yang bermakna.
                </p>
                <a href="{{ route('public.about') }}"
                   class="mt-7 inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-bold text-blue-950 shadow-lg shadow-yellow-400/15 transition-all hover:-translate-y-0.5 hover:bg-yellow-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-300 focus-visible:ring-offset-2 focus-visible:ring-offset-blue-950">
                    Kenali PMII lebih dekat <x-heroicon-o-arrow-right class="h-4 w-4"/>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection