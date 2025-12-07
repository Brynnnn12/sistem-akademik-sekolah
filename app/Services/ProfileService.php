<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(
        protected ProfileRepository $profileRepository
    ) {}

    public function getProfileForUser(User $user): Profile
    {
        // Best Practice: Pastikan profile selalu ada saat diedit.
        // Jika belum ada, buat profile kosong.
        return $this->profileRepository->findByUserId($user->id)
            ?? $this->profileRepository->create([
                'user_id' => $user->id,
                'nip' => 'TEMP-' . $user->id, // Default temporary NIP
                'nama' => 'Nama Belum Diisi',
                'no_hp' => '000000000000',
                'alamat' => 'Alamat belum diisi',
                'jenis_kelamin' => 'laki-laki',
                'photo' => null,
            ]);
    }

    public function updateProfile(User $user, array $data): Profile
    {
        return DB::transaction(function () use ($user, $data) {
            $profile = $this->getProfileForUser($user);

            // Handle Photo
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                $data['photo'] = $this->handlePhotoUpload($data['photo'], $profile->photo);
            }

            // Update User Email (Jika berubah)
            if (isset($data['email']) && $data['email'] !== $user->email) {
                $this->updateUserEmail($user, $data['email']);
            }

            // Update Profile Data
            // Filter hanya field yang relevan untuk tabel profiles
            // Tips: Gunakan $fillable di model untuk safety, tapi unset manual di sini juga oke
            unset($data['email']);

            return $this->profileRepository->update($profile, $data);
        });
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        // Kita tidak perlu cek password lama lagi di sini,
        // karena sudah divalidasi oleh FormRequest 'current_password'
        $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }

    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user) {
            $profile = $this->profileRepository->findByUserId($user->id);

            if ($profile?->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            // Delete user otomatis men-delete profile (jika ada cascade di DB)
            // Tapi kita lakukan manual lewat repo jika tidak ada cascade
            if ($profile) {
                $this->profileRepository->delete($profile);
            }

            $user->delete();
        });
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
        $user->email_verified_at = null; // Reset verifikasi jika email ganti
        $user->save();
    }
}
