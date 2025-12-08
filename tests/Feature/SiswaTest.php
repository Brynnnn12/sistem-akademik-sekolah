<?php

use App\Models\Siswa;
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

test('halaman daftar siswa dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('siswa.index'));

    $response->assertStatus(200);
});

test('siswa dapat dibuat', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $siswaData = [
        'nis' => '1234567890',
        'nisn' => '0987654321',
        'nama' => 'Ahmad Fauzi',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-15',
        'alamat' => 'Jl. Sudirman No. 123',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), $siswaData);

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('siswas', [
        'nis' => '1234567890',
        'nisn' => '0987654321',
        'nama' => 'Ahmad Fauzi',
        'jenis_kelamin' => 'L',
        'alamat' => 'Jl. Sudirman No. 123',
    ]);
});

test('siswa dapat diperbarui', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $siswa = Siswa::factory()->create();

    $updateData = [
        'nis' => '1234567891',
        'nisn' => '0987654322',
        'nama' => 'Ahmad Fauzi Updated',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-16',
        'alamat' => 'Jl. Sudirman No. 124',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('siswa.update', $siswa), $updateData);

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');

    $siswa->refresh();
    expect($siswa->nama)->toBe('Ahmad Fauzi Updated');
    expect($siswa->nis)->toBe('1234567891');
});

test('siswa dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $siswa = Siswa::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('siswa.destroy', $siswa));

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');

    $this->assertSoftDeleted($siswa);
});

test('validasi nis wajib diisi', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), [
            'nisn' => '0987654321',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-05-15',
            'alamat' => 'Jl. Sudirman No. 123',
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['nis']);
});

test('validasi nis harus unik', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    Siswa::factory()->create(['nis' => '1234567890']);

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), [
            'nis' => '1234567890', // Duplicate NIS
            'nisn' => '0987654321',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-05-15',
            'alamat' => 'Jl. Sudirman No. 123',
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['nis']);
});

test('validasi jenis kelamin harus L atau P', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), [
            'nis' => '1234567890',
            'nisn' => '0987654321',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'X', // Invalid gender
            'tanggal_lahir' => '2005-05-15',
            'alamat' => 'Jl. Sudirman No. 123',
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['jenis_kelamin']);
});

test('validasi tanggal lahir harus sebelum hari ini', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), [
            'nis' => '1234567890',
            'nisn' => '0987654321',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->addDay()->format('Y-m-d'), // Future date
            'alamat' => 'Jl. Sudirman No. 123',
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['tanggal_lahir']);
});

test('guru dapat melihat siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('siswa.index'));

    $response->assertStatus(200);
});

test('guru dapat membuat siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $siswaData = [
        'nis' => '1234567890',
        'nisn' => '0987654321',
        'nama' => 'Ahmad Fauzi',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-15',
        'alamat' => 'Jl. Sudirman No. 123',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), $siswaData);

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');
});

test('guru dapat mengupdate siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');
    $siswa = Siswa::factory()->create();

    $updateData = [
        'nis' => '1234567891',
        'nama' => 'Ahmad Fauzi Updated',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-16',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('siswa.update', $siswa), $updateData);

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');
});

test('guru tidak dapat menghapus siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');
    $siswa = Siswa::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('siswa.destroy', $siswa));

    $response->assertForbidden();
});

test('kepala sekolah dapat melihat siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');

    $response = $this
        ->actingAs($user)
        ->get(route('siswa.index'));

    $response->assertStatus(200);
});

test('kepala sekolah tidak dapat membuat siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');

    $siswaData = [
        'nis' => '1234567890',
        'nisn' => '0987654321',
        'nama' => 'Ahmad Fauzi',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-15',
        'alamat' => 'Jl. Sudirman No. 123',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('siswa.store'), $siswaData);

    $response->assertForbidden();
});

test('kepala sekolah tidak dapat mengupdate siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');
    $siswa = Siswa::factory()->create();

    $updateData = [
        'nis' => '1234567891',
        'nama' => 'Ahmad Fauzi Updated',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '2005-05-16',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('siswa.update', $siswa), $updateData);

    $response->assertForbidden();
});

test('kepala sekolah tidak dapat menghapus siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');
    $siswa = Siswa::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('siswa.destroy', $siswa));

    $response->assertForbidden();
});

test('admin dapat menghapus siswa', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $siswa = Siswa::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('siswa.destroy', $siswa));

    $response->assertRedirect(route('siswa.index'));
    $response->assertSessionHas('success');
});

test('search siswa berdasarkan nama', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    Siswa::factory()->create(['nama' => 'Ahmad Fauzi']);
    Siswa::factory()->create(['nama' => 'Budi Santoso']);

    $response = $this
        ->actingAs($user)
        ->get(route('siswa.index', ['search' => 'Ahmad']));

    $response->assertStatus(200);
    $response->assertSee('Ahmad Fauzi');
    $response->assertDontSee('Budi Santoso');
});

test('search siswa berdasarkan NIS', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    Siswa::factory()->create(['nis' => '1234567890']);
    Siswa::factory()->create(['nis' => '0987654321']);

    $response = $this
        ->actingAs($user)
        ->get(route('siswa.index', ['search' => '1234567890']));

    $response->assertStatus(200);
    $response->assertSee('1234567890');
    $response->assertDontSee('0987654321');
});
