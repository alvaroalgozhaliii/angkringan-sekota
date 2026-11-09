<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilUsaha;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $profil = ProfilUsaha::getSingleton();
        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'nama_usaha'        => 'required|string|max:255',
            'whatsapp'          => 'nullable|string|max:20',
            'instagram'         => 'nullable|string|max:100',
            'facebook'          => 'nullable|string|max:100',
            'alamat'            => 'nullable|string',
            'teks_tentang_kami' => 'nullable|string',
            'jam_buka'          => 'nullable|string|max:100',
            'email'             => 'nullable|email|max:255',
        ]);

        // Log incoming request for debugging
        \Log::info('Profil update request', $request->only([
            'nama_usaha','whatsapp','instagram','facebook',
            'alamat','teks_tentang_kami','jam_buka','email',
        ]));

        $profil = ProfilUsaha::getSingleton();
        $profil->update($request->only([
            'nama_usaha','whatsapp','instagram','facebook',
            'alamat','teks_tentang_kami','jam_buka','email',
        ]));

        return back()->with('success', 'Profil berhasil disimpan!');
    }
}
