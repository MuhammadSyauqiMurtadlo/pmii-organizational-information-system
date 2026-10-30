<?php

use App\Models\User;
use App\Livewire\Members\MemberForm;
use App\Livewire\Members\MemberIndex;
use App\Livewire\News\NewsIndex;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard.home'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.home'));
    $response->assertOk();
});

test('member and news Livewire screens render', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->actingAs(User::factory()->create());

    Livewire::test(MemberIndex::class)->assertOk();
    Livewire::test(MemberForm::class)->assertOk();
    Livewire::test(NewsIndex::class)->assertOk();
});