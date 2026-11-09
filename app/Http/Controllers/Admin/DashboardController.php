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
        $produkTerlaris   = Produk::where('status', 'tayang')->orderBy('id')->first();
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
