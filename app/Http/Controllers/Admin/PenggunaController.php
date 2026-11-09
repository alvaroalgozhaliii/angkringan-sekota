<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        // Only owner can manage users
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Hanya owner yang dapat mengelola pengguna admin.');
        }
        $pengguna = User::orderBy('created_at', 'desc')->get();
        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'owner') abort(403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:owner,pegawai',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'Pengguna admin berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'owner') abort(403);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}
