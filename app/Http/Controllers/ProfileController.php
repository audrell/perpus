<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Update nama & email
    public function update(Request $request): RedirectResponse
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        // Sync nama ke tabel member juga
        if ($user->member) {
            $user->member->update(['name' => $request->name]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    // Update foto profile sendiri
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::findOrFail(Auth::id());

        // Hapus foto lama jika ada
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo' => $path]);

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
