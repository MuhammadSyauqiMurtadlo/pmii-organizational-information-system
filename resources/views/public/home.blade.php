@extends('layouts.public')
@section('title', 'Beranda - PMII')
@section('meta-description', 'Website resmi PMII - Pergerakan Mahasiswa Islam Indonesia, organisasi mahasiswa Islam terbesar berbasis Ahlussunnah Wal Jamaah.')

@section('content')

{{-- ════════════ HERO SECTION ════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-900 pt-16">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 h-96 w-96 rounded-full bg-emerald-500/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 h-96 w-96 rounded-full bg-teal-400/10 blur-3xl"></div>
    </div>
    {{-- Grid pattern --}}
    <div class="absolute inset-0 opacity-5"
         style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                  linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                background-size: 48px 48px;"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            {{-- Text --}}
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 border border-emerald-500/30 px-4 py-1.5 text-xs font-medium text-emerald-300 mb-6">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Pergerakan Mahasiswa Islam Indonesia
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight mb-6">
                    Bersama
                    <span class="relative inline-block">
                        <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">PMII</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 200 8" preserveAspectRatio="none">
                            <path d="M0,5 Q50,0 100,5 Q150,10 200,5" stroke="url(#grad)" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#6ee7b7"/>
                                    <stop offset="100%" style="stop-color:#5eead4"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                    <br>Bergerak Menuju Perubahan
                </h1>
                <p class="text-base sm:text-lg text-emerald-100/70 max-w-xl mx-auto lg:mx-0 mb-10 leading-relaxed">
                    Organisasi mahasiswa Islam terbesar yang berlandaskan Ahlussunnah Wal Jama'ah,
                    berkomitmen mencetak kader intelektual yang berakhlak mulia.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('public.about') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-7 py-3.5 text-sm font-semibold text-white hover:bg-emerald-400 transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5">
                        Tentang PMII <x-heroicon-o-arrow-right class="h-4 w-4"/>
                    </a>
                    <a href="{{ route('public.activities.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white hover:bg-white/20 transition-all duration-200 backdrop-blur-sm">
                        Lihat Kegiatan
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="flex-shrink-0 grid grid-cols-2 gap-4 lg:gap-5">
                @foreach([
                    ['value' => $stats['total_members']              ?? 0, 'label' => 'Anggota Aktif',    'icon' => '👥'],
                    ['value' => $stats['total_rayons']               ?? 0, 'label' => 'Rayon',            'icon' => '🏛️'],
                    ['value' => $stats['total_activities']           ?? 0, 'label' => 'Kegiatan',         'icon' => '📅'],
                    ['value' => $stats['kaderisasi_stats']['MAPABA'] ?? 0, 'label' => 'Lulus Kaderisasi', 'icon' => '🎓'],
                ] as $stat)
                <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 p-5 text-center hover:bg-white/15 transition-colors">
                    <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
                    <div class="text-3xl font-bold text-white mb-1">{{ number_format($stat['value']) }}</div>
                    <div class="text-xs text-emerald-200/70 font-medium">{{ $stat['label'] }}</div>
                </div>
                @endforeach
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
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2">Terkini</p>
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
                        <span class="inline-flex rounded-full bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-300">
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
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2">Agenda</p>
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
               class="group flex gap-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-800 transition-all duration-200">
                <div class="flex-shrink-0 flex flex-col items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 w-16 h-16">
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400 leading-none">
                        {{ $activity->start_date->format('d') }}
                    </span>
                    <span class="text-xs font-medium text-emerald-500/70 dark:text-emerald-500 uppercase">
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
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2">Struktur</p>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Rayon PMII</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto text-sm leading-relaxed">
                PMII di kampus kami tersebar di berbagai fakultas melalui rayon yang aktif bergerak.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($rayons as $rayon)
            <div class="group text-center rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 p-6 hover:border-emerald-200 dark:hover:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all duration-200">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 group-hover:bg-emerald-600 group-hover:border-emerald-600 transition-all duration-200 shadow-sm">
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
                <div class="mt-3 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                    {{ $rayon->members_count }} anggota
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection