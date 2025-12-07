<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $this->profileService->getProfileForUser($user);

        throw_if(!$profile, ValidationException::withMessages([
            'profile' => 'Profil tidak ditemukan. Silakan hubungi admin.',
        ]));

        $this->authorize('update', $profile);

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            $this->profileService->updateProfile($user, $request->validated());

            return Redirect::route('profile.edit')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $this->profileService->deleteAccount($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Akun berhasil dihapus!');
    }

    public function editPassword(): View
    {
        return view('profile.edit-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        try {
            $this->profileService->updatePassword(
                $request->user(),
                $validated['current_password'],
                $validated['password']
            );

            return Redirect::route('password.edit')
                ->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }
}
