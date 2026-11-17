<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanTransaksi;
use Illuminate\Http\Request;

class PengaturanTransaksiController extends Controller
{
    public function edit()
    {
        $pengaturan = PengaturanTransaksi::current();

        return view('admin.pengaturan.transaksi', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $tunai = $request->boolean('tunai_aktif');
        $qris = $request->boolean('qris_aktif');

        if (!$tunai && !$qris) {
            return back()->with('error', 'Minimal satu metode, tunai atau QRIS, harus tetap aktif.');
        }

        $pengaturan = PengaturanTransaksi::current();
        $pengaturan->update([
            'tunai_aktif' => $tunai,
            'qris_aktif' => $qris,
        ]);

        return back()->with('success', 'Pengaturan transaksi disimpan. Kasir hanya memakai metode yang aktif.');
    }
}
