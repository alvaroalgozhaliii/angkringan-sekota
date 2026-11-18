<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\ActivityLog;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Support\OrderStatus;

class DashboardController extends PegawaiController
{
    public function index()
    {
        $menungguVerifikasi = Transaksi::where('status', OrderStatus::MENUNGGU_VERIFIKASI)->count();
        $menungguPembayaran = Transaksi::where('status', OrderStatus::MENUNGGU_PEMBAYARAN)->count();
        $diproses = Transaksi::whereIn('status', [
            OrderStatus::MENUNGGU_PERSETUJUAN, OrderStatus::DIBAYAR, OrderStatus::DITERIMA,
            OrderStatus::DIPROSES, OrderStatus::DIKIRIM, OrderStatus::SIAP_DIAMBIL,
        ])->count();
        $stokMenipis = Produk::where('stok', '<=', 5)->count();
        $logTerbaru = ActivityLog::with('user')->latest('created_at')->take(6)->get();

        return view('pegawai.dashboard', compact(
            'menungguVerifikasi', 'menungguPembayaran', 'diproses', 'stokMenipis', 'logTerbaru'
        ));
    }
}
