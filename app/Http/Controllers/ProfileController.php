<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = auth()->user();
        $completion = $this->profileService->getProfileCompletion($user);
        
        return view('profile.index', compact('user', 'completion'));
    }

    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        try {
            $this->profileService->updateProfile(auth()->user(), $request->validated());
            
            return redirect()
                ->route('profile.index')
                ->with('success', 'Profile berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        try {
            $this->profileService->updateAvatar(
                auth()->user(),
                $request->file('avatar')
            );

            return back()->with('success', 'Avatar berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeAvatar(): RedirectResponse
    {
        try {
            $this->profileService->removeAvatar(auth()->user());
            
            return back()->with('success', 'Avatar berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $this->profileService->changePassword(
                auth()->user(),
                $request->current_password,
                $request->new_password
            );

            return back()->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStoreBanner(Request $request): RedirectResponse
    {
        $request->validate([
            'store_banner' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        try {
            $this->profileService->updateStoreBanner(
                auth()->user(),
                $request->file('store_banner')
            );

            return back()->with('success', 'Banner toko berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeStoreBanner(): RedirectResponse
    {
        try {
            $this->profileService->removeStoreBanner(auth()->user());
            
            return back()->with('success', 'Banner toko berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStoreLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'store_logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        try {
            $this->profileService->updateStoreLogo(
                auth()->user(),
                $request->file('store_logo')
            );

            return back()->with('success', 'Logo toko berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function removeStoreLogo(): RedirectResponse
    {
        try {
            $this->profileService->removeStoreLogo(auth()->user());
            
            return back()->with('success', 'Logo toko berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
