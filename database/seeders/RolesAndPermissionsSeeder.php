<?php

namespace Database\Seeders;

use App\Models\Komisariat;
use App\Models\Rayon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ───────────────────────────────────────
        $permissions = [
            // Anggota
            'view_dashboard', 'update_own_profile', 'view_announcements',
            'view_activities', 'view_news',

            // Admin Rayon
            'manage_rayon_members', 'create_rayon_members',
            'manage_rayon_activities', 'manage_rayon_gallery',
            'view_rayon_statistics',

            // Admin Komisariat
            'manage_komisariat_members', 'create_komisariat_members',
            'view_all_members', 'manage_news', 'manage_announcements',
            'manage_komisariat_activities', 'manage_komisariat_gallery',
            'view_all_statistics',

            // Super Admin
            'manage_users', 'manage_roles', 'manage_komisariat',
            'manage_rayons', 'reset_passwords', 'view_system_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Roles ────────────────────────────────────────────
        // 1. Anggota / Kader
        $anggota = Role::firstOrCreate(['name' => 'anggota']);
        $anggota->syncPermissions([
            'view_dashboard', 'update_own_profile',
            'view_announcements', 'view_activities', 'view_news',
        ]);

        // 2. Admin Rayon
        $adminRayon = Role::firstOrCreate(['name' => 'admin_rayon']);
        $adminRayon->syncPermissions([
            'view_dashboard', 'update_own_profile',
            'view_announcements', 'view_activities', 'view_news',
            'manage_rayon_members', 'create_rayon_members',
            'manage_rayon_activities', 'manage_rayon_gallery',
            'view_rayon_statistics',
        ]);

        // 3. Admin Komisariat
        $adminKomisariat = Role::firstOrCreate(['name' => 'admin_komisariat']);
        $adminKomisariat->syncPermissions([
            'view_dashboard', 'update_own_profile',
            'view_announcements', 'view_activities', 'view_news',
            'manage_komisariat_members', 'create_komisariat_members',
            'view_all_members', 'manage_news', 'manage_announcements',
            'manage_komisariat_activities', 'manage_komisariat_gallery',
            'view_all_statistics',
        ]);

        // 4. Super Admin — mendapat semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // ── Default Super Admin User ──────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'superadmin@pmii.ac.id'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('pmii@superadmin2024'),
                'status' => 'active',
            ]
        );
        $user->assignRole('super_admin');

        // ── Default Komisariat ─────────────────────────────────
        $komisariat = Komisariat::firstOrCreate(
            ['slug' => 'pmii-komisariat-universitas'],
            [
                'name' => 'PMII Komisariat Universitas',
                'description' => 'Komisariat PMII tingkat universitas',
                'is_active' => true,
            ]
        );

        // ── Default 5 Rayon ───────────────────────────────────
        $rayons = [
            ['name' => 'Rayon Teknik', 'faculty' => 'Fakultas Teknik'],
            ['name' => 'Rayon Ekonomi', 'faculty' => 'Fakultas Ekonomi dan Bisnis'],
            ['name' => 'Rayon Hukum', 'faculty' => 'Fakultas Hukum'],
            ['name' => 'Rayon FISIP', 'faculty' => 'Fakultas Ilmu Sosial dan Politik'],
            ['name' => 'Rayon Pertanian', 'faculty' => 'Fakultas Pertanian'],
        ];

        foreach ($rayons as $rayonData) {
            Rayon::firstOrCreate(
                ['slug' => Str::slug($rayonData['name'])],
                array_merge($rayonData, [
                    'komisariat_id' => $komisariat->id,
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('✅ Roles, permissions, default users, komisariat, dan rayon berhasil dibuat!');
        $this->command->info('📧 Login: superadmin@pmii.ac.id | Password: pmii@superadmin2024');
    }
}
