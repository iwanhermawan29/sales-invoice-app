<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // isi field agent dari request terverifikasi
        $user->fill($request->validated());

        // jaga logika verifikasi email bawaan Breeze
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // setiap update profil oleh agent -> kembali pending (menunggu admin)
        $user->profile_status = \App\Models\User::PROFILE_PENDING;
        $user->profile_approved_by = null;
        $user->profile_approved_at = null;
        // boleh biarkan approval_note lama agar agent tahu catatan sebelumnya
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'Profil diperbarui. Menunggu verifikasi admin.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
