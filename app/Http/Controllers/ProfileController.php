<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function edit(Request $request): View
    {
        // Service akan menjamin profile selalu ada (firstOrCreate)
        // Jadi tidak perlu throw error manual di sini
        $profile = $this->profileService->getProfileForUser($request->user());

        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => $profile,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $this->profileService->updateProfile($request->user(), $request->validated());

            return back('profile.edit')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Throwable $e) {
            // Log error here if needed: Log::error($e);
            return back()->withErrors(['error' => 'Gagal memperbarui profil.']);
        }
    }

    public function editPassword(): View
    {
        return view('profile.edit-password');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        // Tidak perlu try-catch untuk validasi password salah,
        // karena sudah dihandle otomatis oleh UpdatePasswordRequest
        $this->profileService->updatePassword($request->user(), $request->validated('password'));

        return to_route('password.edit')->with('success', 'Password berhasil diperbarui!');
    }

    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $this->profileService->deleteAccount($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus!');
    }
}
