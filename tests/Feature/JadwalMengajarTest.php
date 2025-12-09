<?php

use App\Models\JadwalMengajar;
use App\Models\PenugasanMengajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles for testing
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Guru']);
    Role::create(['name' => 'KepalaSekolah']);

    // Create users with roles
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Admin');

    $this->guru = User::factory()->create();
    $this->guru->assignRole('Guru');

    $this->waliKelas = User::factory()->create();
    $this->waliKelas->assignRole('Guru'); // Wali kelas juga adalah guru

    // Create kelas with wali kelas
    $kelas = \App\Models\Kelas::factory()->create([
        'wali_kelas_id' => $this->waliKelas->id,
    ]);

    // Create penugasan mengajar
    $this->penugasanMengajar = PenugasanMengajar::factory()->create([
        'guru_id' => $this->guru->id,
    ]);

    // Create jadwal mengajar
    $this->jadwalMengajar = JadwalMengajar::factory()->create([
        'penugasan_mengajar_id' => $this->penugasanMengajar->id,
    ]);
});

test('admin can view jadwal mengajar index', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('dashboard.jadwal-mengajar.index'));

    $response->assertStatus(200);
    $response->assertViewHas('jadwalMengajars');
});

test('guru can view jadwal mengajar index', function () {
    $response = $this->actingAs($this->guru)
        ->get(route('dashboard.jadwal-mengajar.index'));

    $response->assertStatus(200);
});

test('wali kelas can view jadwal mengajar index', function () {
    $response = $this->actingAs($this->waliKelas)
        ->get(route('dashboard.jadwal-mengajar.index'));

    $response->assertStatus(200);
});

test('admin can create jadwal mengajar', function () {
    $jadwalData = [
        'penugasan_mengajar_id' => $this->penugasanMengajar->id,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '09:30',
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('dashboard.jadwal-mengajar.store'), $jadwalData);

    $response->assertRedirect(route('dashboard.jadwal-mengajar.index'));
    $this->assertDatabaseHas('jadwal_mengajars', $jadwalData);
});

test('guru cannot create jadwal mengajar', function () {
    $jadwalData = [
        'penugasan_mengajar_id' => $this->penugasanMengajar->id,
        'hari' => 'Selasa',
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:30',
    ];

    $response = $this->actingAs($this->guru)
        ->post(route('dashboard.jadwal-mengajar.store'), $jadwalData);

    $response->assertStatus(403);
});

test('wali kelas cannot create jadwal mengajar', function () {
    $jadwalData = [
        'penugasan_mengajar_id' => $this->penugasanMengajar->id,
        'hari' => 'Rabu',
        'jam_mulai' => '13:00',
        'jam_selesai' => '14:30',
    ];

    $response = $this->actingAs($this->waliKelas)
        ->post(route('dashboard.jadwal-mengajar.store'), $jadwalData);

    $response->assertStatus(403);
});

test('admin can update jadwal mengajar', function () {
    $updateData = [
        'hari' => 'Kamis',
        'jam_mulai' => '14:00',
        'jam_selesai' => '15:30',
    ];

    $response = $this->actingAs($this->admin)
        ->put(route('dashboard.jadwal-mengajar.update', $this->jadwalMengajar), $updateData);

    $response->assertRedirect(route('dashboard.jadwal-mengajar.index'));
    $this->assertDatabaseHas('jadwal_mengajars', array_merge(['id' => $this->jadwalMengajar->id], $updateData));
});

test('guru can update own jadwal mengajar', function () {
    $updateData = [
        'hari' => 'Jumat',
        'jam_mulai' => '15:00',
        'jam_selesai' => '16:30',
    ];

    $response = $this->actingAs($this->guru)
        ->put(route('dashboard.jadwal-mengajar.update', $this->jadwalMengajar), $updateData);

    $response->assertRedirect(route('dashboard.jadwal-mengajar.index'));
    $this->assertDatabaseHas('jadwal_mengajars', array_merge(['id' => $this->jadwalMengajar->id], $updateData));
});

test('guru cannot update other guru jadwal mengajar', function () {
    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('Guru');

    $otherPenugasan = PenugasanMengajar::factory()->create([
        'guru_id' => $otherGuru->id,
    ]);

    $otherJadwal = JadwalMengajar::factory()->create([
        'penugasan_mengajar_id' => $otherPenugasan->id,
    ]);

    $updateData = [
        'hari' => 'Sabtu',
        'jam_mulai' => '14:00',
        'jam_selesai' => '15:30',
    ];

    $response = $this->actingAs($this->guru)
        ->put(route('dashboard.jadwal-mengajar.update', $otherJadwal), $updateData);

    $response->assertStatus(403);
});

test('admin can delete jadwal mengajar', function () {
    $response = $this->actingAs($this->admin)
        ->delete(route('dashboard.jadwal-mengajar.destroy', $this->jadwalMengajar));

    $response->assertRedirect(route('dashboard.jadwal-mengajar.index'));
    $this->assertDatabaseMissing('jadwal_mengajars', ['id' => $this->jadwalMengajar->id]);
});

test('guru can delete own jadwal mengajar', function () {
    $response = $this->actingAs($this->guru)
        ->delete(route('dashboard.jadwal-mengajar.destroy', $this->jadwalMengajar));

    $response->assertRedirect(route('dashboard.jadwal-mengajar.index'));
    $this->assertDatabaseMissing('jadwal_mengajars', ['id' => $this->jadwalMengajar->id]);
});

test('guru cannot delete other guru jadwal mengajar', function () {
    $otherGuru = User::factory()->create();
    $otherGuru->assignRole('Guru');

    $otherPenugasan = PenugasanMengajar::factory()->create([
        'guru_id' => $otherGuru->id,
    ]);

    $otherJadwal = JadwalMengajar::factory()->create([
        'penugasan_mengajar_id' => $otherPenugasan->id,
    ]);

    $response = $this->actingAs($this->guru)
        ->delete(route('dashboard.jadwal-mengajar.destroy', $otherJadwal));

    $response->assertStatus(403);
});

test('validation fails when required fields missing', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('dashboard.jadwal-mengajar.store'), []);

    $response->assertSessionHasErrors(['penugasan_mengajar_id', 'hari', 'jam_mulai', 'jam_selesai']);
});

test('validation fails when jam mulai after jam selesai', function () {
    $jadwalData = [
        'penugasan_mengajar_id' => $this->penugasanMengajar->id,
        'hari' => 'Senin',
        'jam_mulai' => '14:00',
        'jam_selesai' => '10:00',
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('dashboard.jadwal-mengajar.store'), $jadwalData);

    $response->assertSessionHasErrors(['jam_mulai', 'jam_selesai']);
});

test('can search jadwal mengajar', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('dashboard.jadwal-mengajar.index', ['search' => $this->penugasanMengajar->kelas->nama_kelas]));

    $response->assertStatus(200);
    $response->assertViewHas('jadwalMengajars');
});

test('can filter jadwal by hari', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('dashboard.jadwal-mengajar.index', ['hari' => 'Senin']));

    $response->assertStatus(200);
    $response->assertViewHas('jadwalMengajars');
});
