<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMII') - Pergerakan Mahasiswa Islam Indonesia</title>
    <meta name="description" content="@yield('meta-description', 'Website resmi PMII - Pergerakan Mahasiswa Islam Indonesia')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-white dark:bg-gray-950 font-sans antialiased">

{{-- Navbar --}}
<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">
                    PM
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">PMII</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pergerakan Mahasiswa Islam</p>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach([
                    ['route' => 'public.home',           'label' => 'Beranda'],
                    ['route' => 'public.about',          'label' => 'Tentang'],
                    ['route' => 'public.news.index',     'label' => 'Berita'],
                    ['route' => 'public.activities.index','label' => 'Kegiatan'],
                    ['route' => 'public.gallery.index',  'label' => 'Galeri'],
                    ['route' => 'public.contact',        'label' => 'Kontak'],
                ] as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                          {{ request()->routeIs($nav['route'])
                              ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20'
                              : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    {{ $nav['label'] }}
                </a>
                @endforeach
            </div>

            {{-- Auth Button --}}
            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard.home') }}"
                   class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4"/>
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    Masuk
                </a>
                @endauth

                {{-- Mobile hamburger --}}
                <button x-on:click="open = !open" class="md:hidden text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    <x-heroicon-o-bars-3 class="h-6 w-6" x-show="!open"/>
                    <x-heroicon-o-x-mark class="h-6 w-6" x-show="open" x-cloak/>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition x-cloak
         class="md:hidden border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 space-y-1">
        @foreach([
            ['route' => 'public.home',            'label' => 'Beranda'],
            ['route' => 'public.about',           'label' => 'Tentang'],
            ['route' => 'public.news.index',      'label' => 'Berita'],
            ['route' => 'public.activities.index','label' => 'Kegiatan'],
            ['route' => 'public.gallery.index',   'label' => 'Galeri'],
            ['route' => 'public.contact',         'label' => 'Kontak'],
        ] as $nav)
        <a href="{{ route($nav['route']) }}" x-on:click="open = false"
           class="block px-3 py-2 text-sm font-medium rounded-lg transition-colors
                  {{ request()->routeIs($nav['route'])
                      ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            {{ $nav['label'] }}
        </a>
        @endforeach
    </div>
</nav>

{{-- Page Content --}}
@yield('content')

{{-- Footer --}}
<footer class="bg-gray-950 text-gray-400 mt-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">PM</div>
                    <p class="text-white font-bold text-sm">PMII</p>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Pergerakan Mahasiswa Islam Indonesia — Bergerak bersama membangun peradaban dengan ilmu dan akhlak.
                </p>
            </div>

            {{-- Links --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Navigasi</p>
                <ul class="space-y-2 text-xs">
                    @foreach([
                        ['route' => 'public.about',          'label' => 'Tentang PMII'],
                        ['route' => 'public.news.index',     'label' => 'Berita'],
                        ['route' => 'public.activities.index','label' => 'Kegiatan'],
                        ['route' => 'public.gallery.index',  'label' => 'Galeri'],
                        ['route' => 'public.contact',        'label' => 'Kontak'],
                    ] as $link)
                    <li><a href="{{ route($link['route']) }}" class="hover:text-white transition-colors">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-4">Kontak</p>
                <p class="text-xs leading-relaxed">
                    Untuk informasi lebih lanjut, silakan kunjungi halaman kontak kami.
                </p>
                <a href="{{ route('public.contact') }}"
                   class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-emerald-400 hover:text-emerald-300 transition-colors">
                    Hubungi Kami
                    <x-heroicon-o-arrow-right class="h-3.5 w-3.5"/>
                </a>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-600">
            © {{ date('Y') }} PMII. Semua hak dilindungi.
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>