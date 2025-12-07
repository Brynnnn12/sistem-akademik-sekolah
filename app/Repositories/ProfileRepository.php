<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    /**
     * Mencari profil berdasarkan ID User.
     * Mengembalikan null jika tidak ditemukan.
     */
    public function findByUserId(int|string $userId): ?Profile
    {
        return Profile::where('user_id', $userId)->first();
    }

    /**
     * Membuat profil baru.
     */
    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    /**
     * Mengupdate profil yang ada.
     */
    public function update(Profile $profile, array $data): Profile
    {
        // Method update() di Laravel mengembalikan boolean, 
        // tapi model ($profile) di memori akan otomatis terupdate datanya.
        $profile->update($data);

        return $profile;
    }

    /**
     * Menghapus profil.
     */
    public function delete(Profile $profile): bool
    {
        // delete() bisa mengembalikan null atau boolean
        return (bool) $profile->delete();
    }
}
