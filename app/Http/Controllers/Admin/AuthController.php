<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $this->redirectAfterLogin();
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'username.max' => 'Username atau email terlalu panjang.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $identifier = trim($request->string('username')->toString());
        $credentials = [
            (filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'name') => $identifier,
            'password' => $request->string('password')->toString(),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (! Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['username' => 'Akun Anda tidak memiliki akses ke dashboard.']);
            }
            $request->session()->regenerate();

            return $this->redirectAfterLogin();
        }

        return back()->withErrors(['username' => 'Username/email atau password salah.'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }

    private function redirectAfterLogin()
    {
        return Auth::user()->isSuperAdmin()
            ? redirect()->route('admin.pengguna.index')
            : (Auth::user()->isOwner()
                ? redirect()->route('owner.dashboard')
                : redirect()->route('pegawai.dashboard'));
    }
}
