<?php

namespace App\Http\Controllers;

use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $userId,
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'full_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $user = Auth::user();
        $user->update($request->only('username', 'email', 'full_name', 'bio'));

        if ($request->hasFile('profile_photo')) {
            try {
                // Hapus foto lama jika ada
                if ($user->profile_photo) {
                    $this->imageUploadService->delete($user->profile_photo);
                }

                // Upload foto baru dengan optimasi
                $path = $this->imageUploadService->upload(
                    $request->file('profile_photo'),
                    'profiles'
                );
                $user->update(['profile_photo' => $path]);
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload foto: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Profile berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated');
    }

    public function updatePrivacy(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'required|boolean',
            'email_notifications' => 'required|boolean',
            'forum_notifications' => 'required|boolean',
        ]);

        $user = Auth::user();
        $user->update($request->only('profile_visibility', 'email_notifications', 'forum_notifications'));

        return redirect()->back()->with('success', 'Privacy settings updated');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = Auth::user();

        // Hapus foto profil jika ada
        if ($user->profile_photo) {
            $this->imageUploadService->delete($user->profile_photo);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dihapus');
    }
}
