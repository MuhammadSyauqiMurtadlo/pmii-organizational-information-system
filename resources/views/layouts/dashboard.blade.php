<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PMII Sistem Informasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 dark:bg-gray-950 font-sans antialiased">

<div x-data="{ sidebarOpen: false }" class="flex h-full">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-on:click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 transform bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">

        {{-- Logo --}}
        <div class="flex h-16 items-center gap-3 px-6 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">
                PM
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white leading-none">PMII</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Informasi</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            @php $user = auth()->user(); @endphp

            <x-sidebar-link :href="route('dashboard.home')" icon="squares-2x2" :active="request()->routeIs('dashboard.home')">
                Dashboard
            </x-sidebar-link>

            @if($user->hasAnyPermission(['manage_rayon_members', 'view_all_members']))
            <x-sidebar-link :href="route('dashboard.members.index')" icon="users" :active="request()->routeIs('dashboard.members.*')">
                Anggota
            </x-sidebar-link>
            @endif

            @if($user->hasPermissionTo('manage_news'))
            <x-sidebar-link :href="route('dashboard.news.index')" icon="newspaper" :active="request()->routeIs('dashboard.news.*')">
                Berita
            </x-sidebar-link>
            @endif

            @if($user->hasAnyPermission(['manage_rayon_activities', 'manage_komisariat_activities']))
            <x-sidebar-link :href="route('dashboard.activities.index')" icon="calendar-days" :active="request()->routeIs('dashboard.activities.*')">
                Kegiatan
            </x-sidebar-link>
            @endif

            @if($user->hasAnyPermission(['manage_rayon_gallery', 'manage_komisariat_gallery']))
            <x-sidebar-link :href="route('dashboard.gallery.index')" icon="photo" :active="request()->routeIs('dashboard.gallery.*')">
                Galeri
            </x-sidebar-link>
            @endif

            @if($user->hasPermissionTo('manage_announcements'))
            <x-sidebar-link :href="route('dashboard.announcements.index')" icon="megaphone" :active="request()->routeIs('dashboard.announcements.*')">
                Pengumuman
            </x-sidebar-link>
            @endif

            @if($user->isSuperAdmin() || $user->isAdminKomisariat())
            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-600">Admin</p>
            </div>
            @endif

            @if($user->isSuperAdmin() || $user->isAdminKomisariat())
            <x-sidebar-link :href="route('dashboard.users.index')" icon="user-group" :active="request()->routeIs('dashboard.users.*')">
                Pengguna
            </x-sidebar-link>
            @endif

            @if($user->isSuperAdmin())
            <x-sidebar-link :href="route('dashboard.rayons.index')" icon="building-library" :active="request()->routeIs('dashboard.rayons.*')">
                Rayon
            </x-sidebar-link>
            @endif
        </nav>

        {{-- User profile footer --}}
        <div class="border-t border-gray-200 dark:border-gray-800 p-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     class="h-9 w-9 rounded-full object-cover ring-2 ring-emerald-100 dark:ring-emerald-900/50 flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{ $user->roles->first()?->name ?? 'Anggota' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                            class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="h-5 w-5"/>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">

        {{-- Top Bar --}}
        <header class="flex h-16 items-center gap-4 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 lg:px-6 flex-shrink-0">
            <button x-on:click="sidebarOpen = true"
                    class="lg:hidden text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-heroicon-o-bars-3 class="h-6 w-6"/>
            </button>
            <div class="flex-1 min-w-0">
                <h1 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('public.home') }}" target="_blank"
                   class="hidden sm:inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4"/>
                    Lihat Website
                </a>
                <livewire:dashboard.notification-bell />
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mx-4 lg:mx-6 mt-4 flex items-center gap-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-300">
            <x-heroicon-o-check-circle class="h-5 w-5 flex-shrink-0"/>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="mx-4 lg:mx-6 mt-4 flex items-center gap-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
            <x-heroicon-o-x-circle class="h-5 w-5 flex-shrink-0"/>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>