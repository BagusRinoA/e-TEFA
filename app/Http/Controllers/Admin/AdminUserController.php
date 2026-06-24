<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update role user. Hanya superadmin yang bisa mengubah role.
     */
    public function updateRole(Request $request, User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only superadmin can change user roles.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Superadmin role cannot be changed.');
        }

        $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        $user->update(['role' => $request->role]);

        $name = $user->full_name ?? $user->username;
        return back()->with('success', "Role {$name} berhasil diubah menjadi " . ucfirst($request->role) . ".");
    }
}
