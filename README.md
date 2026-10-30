# Sistem Informasi Organisasi PMII

Aplikasi web untuk membantu publikasi informasi dan pengelolaan kegiatan organisasi PMII (Pergerakan Mahasiswa Islam Indonesia). Pengunjung dapat melihat informasi organisasi, berita, kegiatan, dan galeri; pengurus mengelola data melalui dashboard dengan akses berbasis peran.

## Gambaran untuk pengguna

Website menyediakan halaman publik dan dashboard internal. Halaman publik menampilkan informasi PMII, berita, agenda kegiatan, dan galeri. Dashboard membantu pengurus mengelola anggota dan jenjang kaderisasi, berita, kegiatan beserta peserta, galeri, pengumuman, pengguna, serta data rayon. Menu dan tindakan di dashboard dibatasi sesuai peran dan izin pengguna.

## Fitur utama

- **Informasi publik:** beranda, tentang organisasi, kontak, berita, detail berita, kegiatan, detail kegiatan, dan galeri.
- **Pengelolaan anggota:** data anggota dan pencatatan jenjang kaderisasi.
- **Pengelolaan kegiatan:** publikasi kegiatan, pengelolaan peserta, dan informasi kegiatan.
- **Publikasi organisasi:** pengelolaan berita, galeri, dan pengumuman.
- **Dashboard:** ringkasan statistik, aktivitas terbaru, dan pengumuman.
- **Pengaturan organisasi:** pengelolaan rayon, komisariat, serta pengguna sesuai kewenangan.
- **Kontrol akses:** peran anggota, Admin Rayon, Admin Komisariat, dan Super Admin dengan permission berbeda.
- **Autentikasi:** autentikasi dan verifikasi email untuk area dashboard.

## Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 4, Volt, dan Laravel Fortify
- Spatie Laravel Permission untuk peran dan izin
- Blade, Tailwind CSS 4, Alpine.js, dan Vite
- SQLite sebagai konfigurasi database default; konfigurasi Laravel juga menyediakan MySQL/MariaDB dan PostgreSQL
- Pest/PHPUnit dan Laravel Pint untuk pengujian dan pemeriksaan format kode

> **Catatan dependensi:** project menggunakan `livewire/flux`. Instalasi Composer pada lingkungan baru dapat memerlukan kredensial atau lisensi Flux yang valid.

## Persiapan lokal

Pastikan PHP 8.2 atau lebih baru beserta ekstensi Laravel yang dibutuhkan, Composer, Node.js dan npm tersedia. Jalankan perintah berikut dari direktori project.

1. Pasang dependensi PHP dan JavaScript:

   ```bash
   composer install
   npm install
   ```

2. Buat file konfigurasi lokal dan kunci aplikasi:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Di PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti `cp`.

3. Siapkan database:

   SQLite digunakan secara default. Buat file database bila belum ada:

   ```bash
   php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
   ```

   Pastikan `DB_CONNECTION=sqlite` di `.env`, lalu jalankan migrasi dan seeder:

   ```bash
   php artisan migrate --seed
   ```

   Untuk menggunakan MySQL/MariaDB atau PostgreSQL, atur `DB_CONNECTION`, kredensial, dan nama database di `.env` sebelum migrasi.

4. Kompilasi asset frontend:

   ```bash
   npm run build
   ```

5. Jalankan server pengembangan:

   ```bash
   composer run dev
   ```

   Perintah ini menjalankan server Laravel, worker antrean, dan Vite. Aplikasi biasanya dapat dibuka di `http://localhost:8000`.

   Alternatifnya, jalankan `php artisan serve` dan `npm run dev` pada terminal terpisah.

### Data awal

Seeder membuat role dan permission, satu komisariat, lima rayon contoh, serta akun Super Admin awal. Kredensial akun awal ditetapkan di `database/seeders/RolesAndPermissionsSeeder.php`.

**Akun seed hanya untuk pengembangan lokal.** Segera ganti password atau buat akun administrator yang aman sebelum aplikasi digunakan bersama atau dipublikasikan. Jangan memakai data contoh maupun konfigurasi `.env` lokal untuk deployment produksi.

## Menjalankan pengujian dan pemeriksaan kode

```bash
php artisan test
composer lint:check
npm run build
```

`composer test` menjalankan pemeriksaan format Pint dan test suite Laravel. Pengujian menggunakan SQLite in-memory sesuai konfigurasi `phpunit.xml`.

## Struktur project

| Direktori | Isi |
| --- | --- |
| `app/Http/Controllers/` | Controller halaman publik dan dashboard |
| `app/Livewire/` | Komponen interaktif untuk data anggota, kegiatan, berita, galeri, pengumuman, dan dashboard |
| `app/Models/` | Model domain organisasi dan relasi data |
| `app/Policies/` | Kebijakan otorisasi |
| `app/Services/` | Logika layanan, termasuk statistik dashboard |
| `database/migrations/` | Skema database |
| `database/seeders/` | Data awal dan konfigurasi role/permission |
| `resources/views/` | Tampilan Blade |
| `routes/web.php` | Rute halaman publik dan dashboard |
| `tests/` | Test unit dan feature |

## Peran dan akses

- **Anggota:** akses dashboard dan fitur sesuai izin anggota.
- **Admin Rayon:** mengelola data dan kegiatan pada lingkup rayon sesuai permission.
- **Admin Komisariat:** mengelola data pada lingkup komisariat, termasuk publikasi berita dan pengumuman sesuai permission.
- **Super Admin:** memiliki kewenangan administratif tertinggi, termasuk pengelolaan rayon dan pengguna.

Akses spesifik ditentukan oleh role dan permission aplikasi; tidak semua menu tersedia untuk setiap pengguna.

## Untuk kontributor dan perekrut

Project ini menunjukkan implementasi aplikasi Laravel berbasis MVC dengan pemisahan controller, model, service, policy, Blade, serta komponen Livewire. Cakupan kode juga mencakup relasi dan migrasi database, autentikasi, otorisasi berbasis role/permission, validasi alur konten, pengujian feature, dan pipeline CI untuk test serta code style.

Kontribusi dapat dimulai dengan membuat branch dari branch kerja yang disepakati, menjaga perubahan tetap terfokus, dan menjalankan test serta pemeriksaan kode sebelum mengajukan pull request. Jangan sertakan file `.env`, kredensial, atau data pribadi pada commit.

## Lisensi

Lisensi project belum dinyatakan di repository ini. Hubungi pemilik project sebelum menggunakan atau mendistribusikan project di luar kebutuhan yang telah disetujui.
