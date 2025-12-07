<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    public function findByUserId(int|string $userId): ?Profile
    {
        return Profile::where('user_id', $userId)->first();
    }

    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    public function update(Profile $profile, array $data): Profile
    {
        // fill() + save() lebih aman daripada update() massal
        // jika kita ingin memicu event model Eloquent (seperti Observer)
        $profile->fill($data);

        if ($profile->isDirty()) {
            $profile->save();
        }

        return $profile;
    }

    public function delete(Profile $profile): bool
    {
        return (bool) $profile->delete();
    }
}
