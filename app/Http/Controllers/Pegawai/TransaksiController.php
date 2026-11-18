<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\PengaturanTransaksi;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends PegawaiController
{
    public function edit()
    {
        return view('pegawai.transaksi.index', [
            'pengaturan' => PengaturanTransaksi::current(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'tunai_aktif' => 'nullable|boolean',
            'tunai_syarat' => 'nullable|string|max:1000',
            'tunai_instruksi' => 'nullable|string|max:2000',
            'pengiriman_aktif' => 'nullable|boolean',
            'ambil_di_toko_aktif' => 'nullable|boolean',
            'area_pengiriman' => 'nullable|string|max:2000',
            'tarif_pengiriman' => 'nullable|numeric|min:0|max:9999999',
            'alamat_toko' => 'nullable|string|max:500',
        ]);

        $pengaturan = PengaturanTransaksi::current();
        $tunai = $request->boolean('tunai_aktif');
        $qris = $pengaturan->qris_aktif;
        $pengiriman = $request->boolean('pengiriman_aktif');
        $ambil = $request->boolean('ambil_di_toko_aktif');

        if (! $tunai && ! $qris) {
            return back()->with('error', 'Minimal satu metode pembayaran (tunai atau QRIS) harus aktif.')->withInput();
        }
        if (! $pengiriman && ! $ambil) {
            return back()->with('error', 'Minimal satu cara pesanan (pengiriman atau ambil di toko) harus aktif.')->withInput();
        }
        $sebelum = $this->ringkas($pengaturan);
        $perubahan = [
            'tunai_aktif' => $tunai,
            'tunai_syarat' => $data['tunai_syarat'] ?: null,
            'tunai_instruksi' => $data['tunai_instruksi'] ?: null,
            'pengiriman_aktif' => $pengiriman,
            'ambil_di_toko_aktif' => $ambil,
            'area_pengiriman' => $data['area_pengiriman'] ?: null,
            'tarif_pengiriman' => $data['tarif_pengiriman'] ?? 0,
            'alamat_toko' => $data['alamat_toko'] ?: null,
        ];

        DB::transaction(function () use ($pengaturan, $perubahan, $sebelum) {
            $pengaturan->update($perubahan);
            ActivityLogger::log(
                'Pengaturan Transaksi',
                'Ubah pengaturan transaksi',
                $sebelum,
                $this->ringkas($pengaturan->fresh()),
                'pengaturan_transaksi',
                $pengaturan->id
            );
        });

        return back()->with('success', 'Pengaturan transaksi berhasil disimpan.');
    }

    private function ringkas(PengaturanTransaksi $pengaturan): array
    {
        return [
            'tunai' => $pengaturan->tunai_aktif ? 'aktif' : 'nonaktif',
            'qris' => $pengaturan->qris_aktif ? 'aktif' : 'nonaktif',
            'merchant_qris' => $pengaturan->qris_nama_merchant ?: '-',
            'gambar_qris' => $pengaturan->qris_gambar ?: '-',
            'pengiriman' => $pengaturan->pengiriman_aktif ? 'aktif' : 'nonaktif',
            'ambil_di_toko' => $pengaturan->ambil_di_toko_aktif ? 'aktif' : 'nonaktif',
            'tarif_pengiriman' => $pengaturan->tarif_pengiriman,
            'batas_bayar_menit' => $pengaturan->batas_bayar_menit,
        ];
    }
}
