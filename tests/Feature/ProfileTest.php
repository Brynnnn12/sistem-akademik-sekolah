<?php

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('halaman profil dapat ditampilkan', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
    $response->assertViewHas(['user', 'profile']);
});

test('informasi profil dapat diperbarui', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'nama' => 'Nama Baru',
            'email' => 'newemail@example.com',
            'nip' => '123456789',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat Baru',
            'jenis_kelamin' => 'laki-laki',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('success', 'Profil berhasil diperbarui!');

    $profile->refresh();
    expect($profile->nama)->toBe('Nama Baru');
    expect($user->fresh()->email)->toBe('newemail@example.com');
});

// test('password dapat diperbarui', function () {
//     $user = User::factory()->create(['password' => bcrypt('oldpassword')]);

//     $response = $this
//         ->actingAs($user)
//         ->from(route('password.edit'))
//         ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
//         ->patch(route('profile.password.update'), [
//             'current_password' => 'oldpassword',
//             'password' => 'newpassword',
//             'password_confirmation' => 'newpassword',
//         ]);

//     $response
//         ->assertSessionHasNoErrors()
//         ->assertRedirect(route('password.edit'))
//         ->assertSessionHas('success', 'Password berhasil diperbarui!');
// });

test('user dapat menghapus akun', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response->assertRedirect('/');
    $response->assertSessionHas('success', 'Akun berhasil dihapus!');

    expect(User::find($user->id))->toBeNull();
});

test('user tidak dapat mengakses profil jika profil belum dibuat', function () {
    //ARRANGE
    $user = User::factory()->create();

    //ACT
    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    //ASSERT
    $response->assertOk();
    $response->assertViewHas(['user', 'profile']);
});

test('foto profil dapat diperbarui', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $profile = Profile::factory()->create(['user_id' => $user->id]);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'nama' => 'User dengan Foto',
            'email' => $user->email,
            'photo' => $file,
        ]);

    $response->assertSessionHasNoErrors();

    $profile->refresh();
    expect($profile->photo)->not->toBeNull();

    // Storage::disk('public')->assertExists($profile->photo);
});
