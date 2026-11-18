<?php

namespace App\Http\Controllers\Pegawai;

use App\Support\ActivityLogger;
use App\Support\UploadGambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfilController extends PegawaiController
{
    public function edit()
    {
        return view('pegawai.profil.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:'.UploadGambar::UKURAN_MAKSIMAL,
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'kontak.max' => 'Kontak maksimal 50 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $sebelum = [
            'nama' => $user->name,
            'kontak' => $user->kontak ?: '-',
            'foto' => $user->foto ?: '-',
        ];

        $perubahan = [
            'name' => $data['name'],
            'kontak' => $data['kontak'] ?: null,
        ];

        if ($request->hasFile('foto')) {
            $perubahan['foto'] = UploadGambar::simpan($request->file('foto'), 'profil');
        }

        DB::transaction(function () use ($user, $perubahan, $sebelum) {
            $user->update($perubahan);
            ActivityLogger::log(
                'Profil',
                'Ubah profil sendiri',
                $sebelum,
                [
                    'nama' => $user->name,
                    'kontak' => $user->kontak ?: '-',
                    'foto' => $user->foto ?: '-',
                ],
                'user',
                $user->id
            );
        });

        if (isset($perubahan['foto']) && $sebelum['foto'] !== '-') {
            UploadGambar::hapus($sebelum['foto']);
        }

        return back()->with('success', 'Profil berhasil disimpan.');
    }

    public function editPassword()
    {
        return view('pegawai.profil.password');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/',
            ],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak sama.',
            'password_baru.regex' => 'Password baru harus memuat huruf kecil, huruf besar, dan angka.',
        ]);

        $user = Auth::user();
        if (! Hash::check($data['password_lama'], $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput($request->except('password_lama', 'password_baru', 'password_baru_confirmation'));
        }
        if (Hash::check($data['password_baru'], $user->password)) {
            return back()->withErrors(['password_baru' => 'Password baru harus berbeda dari password lama.']);
        }

        DB::transaction(function () use ($user) {
            $user->update(['password' => Hash::make(request()->string('password_baru')->toString())]);
            ActivityLogger::log('Profil', 'Ubah password sendiri', null, null, 'user', $user->id);
        });

        return back()->with('success', 'Password berhasil diubah.');
    }
}
