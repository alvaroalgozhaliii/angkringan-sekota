<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Support\DashboardRingkasan;
use App\Support\OrderStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $statusAktif = [
            OrderStatus::MENUNGGU_PERSETUJUAN,
            OrderStatus::MENUNGGU_PEMBAYARAN,
            OrderStatus::MENUNGGU_VERIFIKASI,
            OrderStatus::DIBAYAR,
            OrderStatus::DITERIMA,
            OrderStatus::DIPROSES,
            OrderStatus::DIKIRIM,
            OrderStatus::SIAP_DIAMBIL,
        ];

        $pesananAktif = Transaksi::whereIn('status', $statusAktif)->count();
        $menungguVerifikasi = Transaksi::where('status', OrderStatus::MENUNGGU_VERIFIKASI)->count();
        $omzetHariIni = Transaksi::whereDate('created_at', today())
            ->whereNotIn('status', [OrderStatus::DIBATALKAN, OrderStatus::KEDALUWARSA])
            ->sum('total_harga');
        $stokMenipis = Produk::where('stok', '<=', 5)->count();
        $logTerbaru = ActivityLog::with('user')->latest('created_at')->take(6)->get();
        $ringkasan = DashboardRingkasan::hitung();

        return view('admin.dashboard', compact(
            'pesananAktif',
            'menungguVerifikasi',
            'omzetHariIni',
            'stokMenipis',
            'logTerbaru',
            'ringkasan'
        ));
    }
}
