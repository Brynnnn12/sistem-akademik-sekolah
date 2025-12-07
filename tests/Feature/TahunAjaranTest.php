<?php

use App\Models\TahunAjaran;
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

test('halaman daftar tahun ajaran dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('tahun-ajaran.index'));

    $response->assertStatus(200);
});

test('tahun ajaran dapat dibuat', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $tahunAjaranData = [
        'nama' => '2024/2025',
        'semester' => 'ganjil',
        'aktif' => true,
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('tahun-ajaran.store'), $tahunAjaranData);

    $response->assertRedirect(route('tahun-ajaran.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('tahun_ajarans', $tahunAjaranData);
});

test('tahun ajaran dapat diperbarui', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tahunAjaran = TahunAjaran::factory()->create([
        'nama' => '2023/2024',
        'semester' => 'ganjil',
        'aktif' => false,
    ]);

    $updateData = [
        'nama' => '2023/2024 Updated',
        'semester' => 'genap',
        'aktif' => true,
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('tahun-ajaran.update', $tahunAjaran), $updateData);

    $response->assertRedirect(route('tahun-ajaran.index'));
    $response->assertSessionHas('success');

    $tahunAjaran->refresh();
    expect($tahunAjaran->nama)->toBe('2023/2024 Updated');
    expect($tahunAjaran->semester)->toBe('genap');
    expect($tahunAjaran->aktif)->toBe(true);
});

test('tahun ajaran aktif dapat diubah', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    // Buat tahun ajaran aktif
    $activeTahunAjaran = TahunAjaran::factory()->create(['aktif' => true]);

    // Buat tahun ajaran tidak aktif
    $inactiveTahunAjaran = TahunAjaran::factory()->create(['aktif' => false]);

    $response = $this
        ->actingAs($user)
        ->patch(route('tahun-ajaran.set-active', $inactiveTahunAjaran));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Cek bahwa tahun ajaran yang baru aktif
    $inactiveTahunAjaran->refresh();
    expect($inactiveTahunAjaran->aktif)->toBe(true);

    // Cek bahwa tahun ajaran yang lama tidak aktif
    $activeTahunAjaran->refresh();
    expect($activeTahunAjaran->aktif)->toBe(false);
});

test('tahun ajaran aktif tidak dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tahunAjaran = TahunAjaran::factory()->create(['aktif' => true]);

    $response = $this
        ->actingAs($user)
        ->delete(route('tahun-ajaran.destroy', $tahunAjaran));

    $response->assertRedirect();
    $response->assertSessionHasErrors('error');

    $this->assertDatabaseHas('tahun_ajarans', ['id' => $tahunAjaran->id]);
});

test('tahun ajaran tidak aktif dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $tahunAjaran = TahunAjaran::factory()->create(['aktif' => false]);

    $response = $this
        ->actingAs($user)
        ->delete(route('tahun-ajaran.destroy', $tahunAjaran));

    $response->assertRedirect(route('tahun-ajaran.index'));
    $response->assertSessionHas('success');

    $this->assertSoftDeleted($tahunAjaran);
});

test('validasi nama tahun ajaran unik', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    TahunAjaran::factory()->create(['nama' => '2024/2025']);

    $response = $this
        ->actingAs($user)
        ->post(route('tahun-ajaran.store'), [
            'nama' => '2024/2025', // Nama yang sama
            'semester' => 'genap',
            'aktif' => false,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['nama']);
});

test('validasi semester harus ganjil atau genap', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('tahun-ajaran.store'), [
            'nama' => '2024/2025',
            'semester' => 'invalid_semester', // Invalid semester
            'aktif' => false,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['semester']);
});
