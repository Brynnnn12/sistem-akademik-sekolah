<?php

use App\Models\Kelas;
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

test('halaman daftar kelas dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index'));

    $response->assertStatus(200);
});

test('kelas dapat dibuat', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $kelasData = [
        'nama' => '1A',
        'tingkat_kelas' => 1,
        'wali_kelas_id' => $guru->id,
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), $kelasData);

    $response->assertRedirect(route('kelas.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('kelas', [
        'nama' => '1A',
        'tingkat_kelas' => 1,
        'wali_kelas_id' => $guru->id,
    ]);
});

test('kelas dapat diperbarui', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $updateData = [
        'nama' => '2B',
        'tingkat_kelas' => 2,
        'wali_kelas_id' => $guru->id,
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('kelas.update', $kelas), $updateData);

    $response->assertRedirect(route('kelas.index'));
    $response->assertSessionHas('success');

    $kelas->refresh();
    expect($kelas->nama)->toBe('2B');
    expect($kelas->tingkat_kelas)->toBe(2);
});

test('kelas dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('kelas.destroy', $kelas));

    $response->assertRedirect(route('kelas.index'));
    $response->assertSessionHas('success');

    $this->assertSoftDeleted($kelas);
});

test('validasi nama kelas wajib diisi', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'tingkat_kelas' => 1,
            'wali_kelas_id' => $guru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('nama');
});

test('validasi nama kelas harus unik', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru1 = User::factory()->create();
    $guru1->assignRole('Guru');
    $guru2 = User::factory()->create();
    $guru2->assignRole('Guru');

    Kelas::factory()->create(['nama' => '1A']);

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '1A',
            'tingkat_kelas' => 1,
            'wali_kelas_id' => $guru2->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('nama');
});

test('validasi format nama kelas harus angka dan huruf', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '1ABC',
            'tingkat_kelas' => 1,
            'wali_kelas_id' => $guru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('nama');
});

test('validasi tingkat kelas wajib diisi', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => 'KelasA', // nama tanpa angka di depan
            'wali_kelas_id' => $guru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('tingkat_kelas');
});

test('validasi tingkat kelas harus antara 1-6', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '7A',
            'tingkat_kelas' => 7,
            'wali_kelas_id' => $guru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('tingkat_kelas');
});

test('validasi wali kelas wajib diisi', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '1A',
            'tingkat_kelas' => 1,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('wali_kelas_id');
});

test('validasi wali kelas harus berperan sebagai guru', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $nonGuru = User::factory()->create(); // No role assigned

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '1A',
            'tingkat_kelas' => 1,
            'wali_kelas_id' => $nonGuru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('wali_kelas_id');
});

test('wali kelas tidak boleh duplikat antar kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    Kelas::factory()->create(['wali_kelas_id' => $guru->id]);

    $response = $this
        ->actingAs($user)
        ->post(route('kelas.store'), [
            'nama' => '2A',
            'tingkat_kelas' => 2,
            'wali_kelas_id' => $guru->id,
        ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('wali_kelas_id');
});

test('kelas dengan siswa tidak dapat dihapus', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();
    $siswa = \App\Models\Siswa::factory()->create();
    $tahunAjaran = \App\Models\TahunAjaran::factory()->create();

    // Attach siswa to kelas through pivot table
    $kelas->siswas()->attach($siswa->id, ['tahun_ajaran_id' => $tahunAjaran->id]);

    $response = $this
        ->actingAs($user)
        ->delete(route('kelas.destroy', $kelas));

    $response->assertRedirect();
    $response->assertSessionHasErrors('kelas');
});

test('admin dapat melihat daftar kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index'));

    $response->assertStatus(200);
});

test('guru dapat melihat daftar kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index'));

    $response->assertStatus(200);
});

test('kepala sekolah dapat melihat daftar kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index'));

    $response->assertStatus(200);
});

test('admin dapat membuat kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.create'));

    $response->assertStatus(200);
});

test('kepala sekolah dapat membuat kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');
    $guru = User::factory()->create();
    $guru->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.create'));

    $response->assertStatus(200);
});

test('guru tidak dapat membuat kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.create'));

    $response->assertStatus(403);
});

test('guru dapat mengedit kelas yang diawali', function () {
    $guru = User::factory()->create();
    $guru->assignRole('Guru');
    $kelas = Kelas::factory()->create(['wali_kelas_id' => $guru->id]);

    $response = $this
        ->actingAs($guru)
        ->get(route('kelas.edit', $kelas));

    $response->assertStatus(200);
});

test('guru tidak dapat mengedit kelas orang lain', function () {
    $guru1 = User::factory()->create();
    $guru1->assignRole('Guru');
    $guru2 = User::factory()->create();
    $guru2->assignRole('Guru');
    $kelas = Kelas::factory()->create(['wali_kelas_id' => $guru2->id]);

    $response = $this
        ->actingAs($guru1)
        ->get(route('kelas.edit', $kelas));

    $response->assertStatus(403);
});

test('admin dapat menghapus kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('kelas.destroy', $kelas));

    $response->assertRedirect(route('kelas.index'));
    $response->assertSessionHas('success');
});

test('kepala sekolah dapat menghapus kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('KepalaSekolah');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('kelas.destroy', $kelas));

    $response->assertRedirect(route('kelas.index'));
    $response->assertSessionHas('success');
});

test('guru tidak dapat menghapus kelas', function () {
    $user = User::factory()->create();
    $user->assignRole('Guru');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('kelas.destroy', $kelas));

    $response->assertStatus(403);
});

test('search kelas berdasarkan nama', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    Kelas::factory()->create(['nama' => '1A']);
    Kelas::factory()->create(['nama' => '2B']);

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index', ['search' => '1A']));

    $response->assertStatus(200);
    $response->assertSee('1A');
    $response->assertDontSee('2B');
});

test('search kelas berdasarkan tingkat', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    Kelas::factory()->create(['nama' => '1A', 'tingkat_kelas' => 1]);
    Kelas::factory()->create(['nama' => '2B', 'tingkat_kelas' => 2]);

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.index', ['search' => '1']));

    $response->assertStatus(200);
    $response->assertSee('1A');
    $response->assertDontSee('2B');
});

test('halaman detail kelas dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.show', $kelas));

    $response->assertStatus(200);
    $response->assertSee($kelas->nama);
});

test('halaman edit kelas dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');
    $kelas = Kelas::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.edit', $kelas));

    $response->assertStatus(200);
    $response->assertSee($kelas->nama);
});

test('halaman create kelas dapat ditampilkan', function () {
    $user = User::factory()->create();
    $user->assignRole('Admin');

    $response = $this
        ->actingAs($user)
        ->get(route('kelas.create'));

    $response->assertStatus(200);
});
