<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilUsaha;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengubah identitas usaha.');
        $profil = ProfilUsaha::getSingleton();

        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengubah identitas usaha.');

        // Validate incoming data
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'teks_tentang_kami' => 'nullable|string',
            'jam_buka' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
        ]);

        $profil = ProfilUsaha::getSingleton();
        $profil->update($request->only([
            'nama_usaha', 'whatsapp', 'instagram', 'facebook', 'tiktok',
            'alamat', 'teks_tentang_kami', 'jam_buka', 'email',
        ]));

        return back()->with('success', 'Profil berhasil disimpan!');
    }
}
