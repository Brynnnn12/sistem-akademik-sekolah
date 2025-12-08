<?php

use App\Models\User;
use App\Models\PenugasanMengajar;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles for testing
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Guru']);
    Role::create(['name' => 'KepalaSekolah']);
});


test('admin can view penugasan mengajar', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $penugasan = PenugasanMengajar::factory()->create();

    $this->actingAs($admin)
        ->get(route('penugasan-mengajar.show', $penugasan))
        ->assertStatus(200);
});

test('admin bisa mengedit penugasan mengajar', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $penugasan = PenugasanMengajar::factory()->create();

    $this->actingAs($admin)
        ->get(route('penugasan-mengajar.edit', $penugasan))
        ->assertStatus(200);
});

test('guru can view their own penugasan mengajar', function () {
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $penugasan = PenugasanMengajar::factory()->create(['guru_id' => $guru->id]);

    $this->actingAs($guru)
        ->get(route('penugasan-mengajar.show', $penugasan))
        ->assertStatus(200);
});

test('guru cannot view other guru penugasan mengajar', function () {
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $otherGuru = User::factory()->create();
    $penugasan = PenugasanMengajar::factory()->create(['guru_id' => $otherGuru->id]);

    $this->actingAs($guru)
        ->get(route('penugasan-mengajar.show', $penugasan))
        ->assertStatus(403);
});
