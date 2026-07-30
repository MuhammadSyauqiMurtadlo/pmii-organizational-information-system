<?php

// use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::view('dashboard', 'dashboard')->name('dashboard');
// });

// require __DIR__.'/settings.php';

use App\Http\Controllers\Dashboard\ActivityManageController;
use App\Http\Controllers\Dashboard\AnnouncementController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\GalleryManageController;
use App\Http\Controllers\Dashboard\MemberController;
use App\Http\Controllers\Dashboard\NewsManageController;
use App\Http\Controllers\Dashboard\RayonController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Public\ActivityController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// ════════════ PUBLIC ROUTES ════════════
Route::name('public.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/tentang', [HomeController::class, 'about'])->name('about');
    Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
    Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
    Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/kegiatan', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/kegiatan/{slug}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
});

// ════════════ DASHBOARD ROUTES (Auth Required) ════════════
Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        // Dashboard home
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        // Member management
        Route::middleware(['can:viewAny,App\Models\Member'])->group(function () {
            Route::get('/anggota', [MemberController::class, 'index'])->name('members.index');
            Route::get('/anggota/tambah', [MemberController::class, 'create'])->name('members.create');
            Route::get('/anggota/{member}', [MemberController::class, 'show'])->name('members.show');
            Route::get('/anggota/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        });

        // News management
        Route::middleware(['permission:manage_news'])->group(function () {
            Route::get('/berita', [NewsManageController::class, 'index'])->name('news.index');
            Route::get('/berita/buat', [NewsManageController::class, 'create'])->name('news.create');
            Route::get('/berita/{news}/edit', [NewsManageController::class, 'edit'])->name('news.edit');
        });

        // Activity management
        Route::get('/kegiatan', [ActivityManageController::class, 'index'])->name('activities.index');
        Route::get('/kegiatan/buat', [ActivityManageController::class, 'create'])->name('activities.create');
        Route::get('/kegiatan/{activity}', [ActivityManageController::class, 'show'])->name('activities.show');
        Route::get('/kegiatan/{activity}/edit', [ActivityManageController::class, 'edit'])->name('activities.edit');

        // Gallery
        Route::get('/galeri', [GalleryManageController::class, 'index'])->name('gallery.index');

        // Announcements
        Route::middleware(['permission:manage_announcements'])->group(function () {
            Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
            Route::get('/pengumuman/buat', [AnnouncementController::class, 'create'])->name('announcements.create');
        });

        // Admin Komisariat + Super Admin
        Route::middleware(['role:super_admin|admin_komisariat'])->group(function () {
            Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        });

        // Super Admin only
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/rayon', [RayonController::class, 'index'])->name('rayons.index');
        });

        // Profile (Volt - Livewire Starter Kit)
        Volt::route('/profil', 'settings.profile')->name('profile');
    });

// ! Penting untuk diperhatikan kode dibawah yang saya komentari (AUTH) karena saya tidak menggunakan fitur auth bawaan laravel, jadi saya menonaktifkan route auth bawaan laravel. Jika anda ingin menggunakan fitur auth bawaan laravel, silahkan hapus komentar pada kode dibawah ini
// require __DIR__.'/auth.php';
