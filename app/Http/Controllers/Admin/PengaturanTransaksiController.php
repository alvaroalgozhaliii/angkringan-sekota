<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanTransaksi;
use App\Support\ActivityLogger;
use App\Support\UploadGambar;
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
        $data = $request->validate([
            'tunai_aktif' => 'nullable|boolean',
            'qris_aktif' => 'nullable|boolean',
            'qris_nama_merchant' => 'nullable|string|max:100',
            'qris_gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:'.UploadGambar::UKURAN_MAKSIMAL,
            'batas_bayar_menit' => 'required|integer|min:1|max:10080',
        ], [
            'qris_gambar.image' => 'Gambar QRIS harus berupa gambar.',
            'qris_gambar.mimes' => 'Format gambar QRIS harus JPG, PNG, atau WebP.',
            'qris_gambar.max' => 'Ukuran gambar QRIS maksimal 2 MB.',
            'batas_bayar_menit.required' => 'Batas waktu bayar wajib diisi.',
            'batas_bayar_menit.integer' => 'Batas waktu bayar harus bilangan bulat.',
            'batas_bayar_menit.min' => 'Batas waktu bayar minimal 1 menit.',
        ]);

        $tunai = $request->boolean('tunai_aktif');
        $qris = $request->boolean('qris_aktif');
        if (! $tunai && ! $qris) {
            return back()->with('error', 'Minimal satu metode, tunai atau QRIS, harus tetap aktif.')->withInput();
        }

        $pengaturan = PengaturanTransaksi::current();
        if ($qris && empty($data['qris_nama_merchant'])) {
            return back()->with('error', 'Nama merchant QRIS wajib diisi saat QRIS aktif.')->withInput();
        }
        if ($qris && ! $request->hasFile('qris_gambar') && ! $pengaturan->qris_gambar) {
            return back()->with('error', 'Gambar QRIS wajib diunggah saat QRIS aktif.')->withInput();
        }

        $sebelum = $this->ringkas($pengaturan);
        $perubahan = [
            'tunai_aktif' => $tunai,
            'qris_aktif' => $qris,
            'qris_nama_merchant' => $data['qris_nama_merchant'] ?: null,
            'batas_bayar_menit' => $data['batas_bayar_menit'],
        ];

        if ($request->hasFile('qris_gambar')) {
            $perubahan['qris_gambar'] = UploadGambar::simpan($request->file('qris_gambar'), 'pengaturan/qris');
        }

        $pengaturan->update($perubahan);
        ActivityLogger::log(
            'Pengaturan Transaksi',
            'Ubah pengaturan pembayaran',
            $sebelum,
            $this->ringkas($pengaturan->fresh()),
            'pengaturan_transaksi',
            $pengaturan->id
        );

        return back()->with('success', 'Pengaturan pembayaran disimpan. QRIS akan tampil di halaman menu dan bukti transfer diperiksa Pegawai.');
    }

    private function ringkas(PengaturanTransaksi $pengaturan): array
    {
        return [
            'tunai' => $pengaturan->tunai_aktif ? 'aktif' : 'nonaktif',
            'qris' => $pengaturan->qris_aktif ? 'aktif' : 'nonaktif',
            'merchant_qris' => $pengaturan->qris_nama_merchant ?: '-',
            'gambar_qris' => $pengaturan->qris_gambar ?: '-',
            'batas_bayar_menit' => $pengaturan->batas_bayar_menit,
        ];
    }
}
