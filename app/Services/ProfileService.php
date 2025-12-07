<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(
        protected ProfileRepository $profileRepository
    ) {}

    public function getProfileForUser(User $user): ?Profile
    {
        return $this->profileRepository->findByUserId($user->id);
    }

    public function updateProfile(User $user, array $data): Profile
    {
        $profile = $this->profileRepository->findByUserId($user->id);

        if (!$profile) {
            $profile = $this->profileRepository->create(['user_id' => $user->id]);
        }

        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->handlePhotoUpload($data['photo'], $profile->photo);
        } else {
            unset($data['photo']);
        }

        $profileData = Arr::only($data, [
            'nip',
            'nama',
            'no_hp',
            'alamat',
            'jenis_kelamin',
            'photo'
        ]);

        $this->profileRepository->update($profile, $profileData);

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $this->updateUserEmail($user, $data['email']);
        }

        return $profile->refresh();
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Password lama salah.');
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }

    public function deleteAccount(User $user): void
    {
        $profile = $this->getProfileForUser($user);
        if ($profile && $profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        Auth::logout();

        $user->delete();
    }

    private function handlePhotoUpload(UploadedFile $file, ?string $oldPhoto): string
    {
        if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return $file->store('photos', 'public');
    }

    private function updateUserEmail(User $user, string $email): void
    {
        $user->email = $email;
        $user->email_verified_at = null;
        $user->save();
    }
}
