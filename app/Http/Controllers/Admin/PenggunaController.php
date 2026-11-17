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

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        $data = $request->validate([
            'role' => 'required|in:owner,pegawai',
        ]);

        if ($user->id === Auth::id() && $data['role'] !== 'owner') {
            return back()->with('error', 'Akun yang sedang dipakai tidak bisa diturunkan dari admin.');
        }

        if ($user->role === 'owner' && $data['role'] !== 'owner' && User::where('role', 'owner')->count() <= 1) {
            return back()->with('error', 'Harus ada minimal satu akun admin.');
        }

        $user->update(['role' => $data['role']]);

        return back()->with('success', 'Hak akses ' . $user->name . ' diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'owner') abort(403);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        if ($user->role === 'owner' && User::where('role', 'owner')->count() <= 1) {
            return back()->with('error', 'Harus ada minimal satu akun admin.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}
