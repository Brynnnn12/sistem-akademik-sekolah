<?php

use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles for testing
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Guru']);
    Role::create(['name' => 'KepalaSekolah']);
});

test('halaman daftar mata pelajaran dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('mata-pelajaran.index'));

    $response->assertStatus(200);
});

test('mata pelajaran dapat dibuat', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $mataPelajaranData = [
        'nama' => 'Matematika',
        'kkm' => 75,
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('mata-pelajaran.store'), $mataPelajaranData);

    $response->assertRedirect(route('mata-pelajaran.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('mata_pelajarans', $mataPelajaranData);
});

test('mata pelajaran dapat diperbarui', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $mataPelajaran = MataPelajaran::factory()->create([
        'nama' => 'Bahasa Indonesia',
        'kode' => 'BINDO101',
        'kkm' => 70,
    ]);

    $updateData = [
        'nama' => 'Bahasa Indonesia Updated',
        'kkm' => 80,
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('mata-pelajaran.update', $mataPelajaran), $updateData);

    $response->assertRedirect(route('mata-pelajaran.index'));
    $response->assertSessionHas('success');

    $mataPelajaran->refresh();
    expect($mataPelajaran->nama)->toBe('Bahasa Indonesia Updated');
    expect($mataPelajaran->kkm)->toBe(80);
});

test('mata pelajaran dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $mataPelajaran = MataPelajaran::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('mata-pelajaran.destroy', $mataPelajaran));

    $response->assertRedirect(route('mata-pelajaran.index'));
    $response->assertSessionHas('success');

    $this->assertSoftDeleted($mataPelajaran);
});

test('validasi kkm harus antara 0 dan 100', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('mata-pelajaran.store'), [
            'nama' => 'Matematika',
            'kode' => 'MATH101',
            'kkm' => 150, // Invalid KKM
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['kkm']);
});

test('guru dapat melihat mata pelajaran', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('mata-pelajaran.index'));

    $response->assertStatus(200);
});

test('guru tidak dapat membuat mata pelajaran', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->post(route('mata-pelajaran.store'), [
            'nama' => 'Matematika',
            'kkm' => 75,
        ]);

    $response->assertForbidden();
});

test('kepala sekolah dapat melihat mata pelajaran', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');

    $response = $this
        ->actingAs($user)
        ->get(route('mata-pelajaran.index'));

    $response->assertStatus(200);
});

test('kepala sekolah tidak dapat membuat mata pelajaran', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');

    $response = $this
        ->actingAs($user)
        ->post(route('mata-pelajaran.store'), [
            'nama' => 'Matematika',
            'kkm' => 75,
        ]);

    $response->assertForbidden();
});
