<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesan;
use App\Models\ProfilUsaha;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $profil           = ProfilUsaha::getSingleton();
        $totalProdukTayang = Produk::where('status', 'tayang')->count();
        $pesanBelumDibaca = Pesan::where('status', 'belum_dibaca')->count();

        // Cari produk terlaris dari data transaksi riil jika ada, atau fallback ke produk pertama
        $terlarisRow = \App\Models\TransaksiDetail::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->whereNotNull('produk_id')
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->first();

        if ($terlarisRow && ($bestProduct = Produk::find($terlarisRow->produk_id))) {
            $produkTerlaris = $bestProduct;
        } else {
            $produkTerlaris = Produk::where('status', 'tayang')->orderBy('id')->first();
        }

        $semuaProduk      = Produk::orderBy('urutan')->orderBy('id')->get();

        // Check database connection
        try {
            DB::connection()->getPdo();
            $dbStatus = 'tersambung';
        } catch (\Exception $e) {
            $dbStatus = 'error';
        }

        return view('admin.dashboard', compact(
            'profil',
            'totalProdukTayang',
            'pesanBelumDibaca',
            'produkTerlaris',
            'semuaProduk',
            'dbStatus'
        ));
    }
}
