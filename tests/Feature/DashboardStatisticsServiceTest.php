<?php

use App\Models\User;
use App\Services\DashboardStatisticsService;
use Illuminate\Support\Facades\DB;

test('monthly member statistics work with sqlite', function () {
    $now = now();
    $komisariatId = DB::table('komisariats')->insertGetId([
        'name' => 'Test Komisariat',
        'slug' => 'test-komisariat',
        'created_at' => $now,
        'updated_at' => $now,
    ]);
    $rayonId = DB::table('rayons')->insertGetId([
        'komisariat_id' => $komisariatId,
        'name' => 'Test Rayon',
        'slug' => 'test-rayon',
        'faculty' => 'Test Faculty',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    foreach (User::factory()->count(2)->create() as $index => $user) {
        DB::table('members')->insert([
            'user_id' => $user->id,
            'rayon_id' => $rayonId,
            'created_at' => $now->copy()->subDays($index),
            'updated_at' => $now,
        ]);
    }

    $stats = app(DashboardStatisticsService::class)->getGlobalStats();

    expect($stats['monthly_members'])->toBe([$now->format('Y-m') => 2]);
});
