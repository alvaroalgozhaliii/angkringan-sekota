<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanPublikController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengirim' => 'required|string|max:255',
            'nomor_wa'      => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'isi_pesan'     => 'required|string|max:2000',
        ]);

        Pesan::create($request->only(['nama_pengirim','nomor_wa','email','isi_pesan']));

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
