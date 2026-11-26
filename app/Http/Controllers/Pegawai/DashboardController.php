<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\ActivityLog;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Support\DashboardRingkasan;
use App\Support\OrderStatus;

class DashboardController extends PegawaiController
{
    public function index()
    {
        $statusAktif = [
            OrderStatus::MENUNGGU_PERSETUJUAN,
            OrderStatus::MENUNGGU_VERIFIKASI,
            OrderStatus::MENUNGGU_PEMBAYARAN,
            OrderStatus::DIBAYAR,
            OrderStatus::DITERIMA,
            OrderStatus::DIPROSES,
            OrderStatus::DIKIRIM,
            OrderStatus::SIAP_DIAMBIL,
        ];

        $pesananAktifCount = Transaksi::whereIn('status', $statusAktif)->count();
        $menungguVerifikasiCount = Transaksi::where('status', OrderStatus::MENUNGGU_VERIFIKASI)->count();

        $omzetHariIni = Transaksi::whereDate('created_at', today())
            ->whereNotIn('status', [OrderStatus::DIBATALKAN, OrderStatus::KEDALUWARSA])
            ->sum('total_harga');

        $transaksiHariIniCount = Transaksi::whereDate('created_at', today())
            ->whereNotIn('status', [OrderStatus::DIBATALKAN, OrderStatus::KEDALUWARSA])
            ->count();

        $stokMenipisCount = Produk::where('stok', '<=', 5)->count();
        $produkStokMenipis = Produk::where('stok', '<=', 5)->orderBy('stok')->take(5)->get();

        $pesananTerbaru = Transaksi::latest('created_at')->take(5)->get();
        $logTerbaru = ActivityLog::latest('created_at')->take(5)->get();
        $ringkasan = DashboardRingkasan::hitung();

        return view('pegawai.dashboard', compact(
            'pesananAktifCount',
            'menungguVerifikasiCount',
            'omzetHariIni',
            'transaksiHariIniCount',
            'stokMenipisCount',
            'produkStokMenipis',
            'pesananTerbaru',
            'logTerbaru',
            'ringkasan'
        ));
    }
}
