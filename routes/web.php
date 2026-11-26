<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengaturanTransaksiController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProfilAkunController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Pegawai\BannerController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\KasirController;
use App\Http\Controllers\Pegawai\KontenController;
use App\Http\Controllers\Pegawai\LogController;
use App\Http\Controllers\Pegawai\PesananController as PegawaiPesananController;
use App\Http\Controllers\Pegawai\ProdukController as PegawaiProdukController;
use App\Http\Controllers\Pegawai\TransaksiController;
use App\Http\Controllers\PesananPublikController;
use App\Http\Controllers\PesanPublikController;
use App\Http\Controllers\UlasanPublikController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Banner;
use App\Models\PengaturanTransaksi;
use App\Models\Produk;
use App\Models\ProfilUsaha;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route;

// ============================================================
//  PUBLIC — Website Profil UMKM (pengunjung)
// ============================================================
Route::get('/', function () {
    $profil = ProfilUsaha::getSingleton();
    $produk = Produk::tayang()->get();
    $ulasan = Testimonial::aktif()->terbaru()->get();
    $banners = Banner::aktif()->get();
    $pengaturanTransaksi = PengaturanTransaksi::current();

    return view('welcome', compact('profil', 'produk', 'ulasan', 'banners', 'pengaturanTransaksi'));
})->name('home');

// Form kirim pesan dari web profil
Route::post('/kirim-pesan', [PesanPublikController::class, 'store'])->name('pesan.store');

// Form kirim ulasan dari web profil
Route::post('/kirim-ulasan', [UlasanPublikController::class, 'store'])->name('ulasan.store');

// Pesanan publik: buat pesanan, unggah bukti QRIS, cek status
Route::post('/pesanan', [PesananPublikController::class, 'store'])->name('pesanan.store');
Route::post('/pesanan/{transaksi}/bukti', [PesananPublikController::class, 'unggahBukti'])->name('pesanan.bukti');
Route::get('/cek-pesanan', [PesananPublikController::class, 'cek'])->name('pesanan.cek');

// ============================================================
//  ADMIN — Panel Admin (/admin/...)
// ============================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (tidak perlu login)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(AdminMiddleware::class)->group(function () {

        Route::get('/profil-pengguna', [ProfilAkunController::class, 'edit'])->name('profil-akun.edit');
        Route::put('/profil-pengguna', [ProfilAkunController::class, 'update'])->name('profil-akun.update');

        Route::middleware('role:super_admin')->group(function () {
            Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
            Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

            Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
            Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
            Route::patch('/pengguna/{user}/role', [PenggunaController::class, 'updateRole'])->name('pengguna.role');
            Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
        });

        Route::middleware('role:owner,super_admin')->group(function () {
            Route::get('/pengaturan/transaksi', [PengaturanTransaksiController::class, 'edit'])->name('pengaturan.transaksi');
            Route::put('/pengaturan/transaksi', [PengaturanTransaksiController::class, 'update'])->name('pengaturan.transaksi.update');

            Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
            Route::get('/pesanan/{transaksi}/koreksi', [PesananController::class, 'edit'])->name('pesanan.edit');
            Route::put('/pesanan/{transaksi}', [PesananController::class, 'update'])->name('pesanan.update');
            Route::patch('/pesanan/{transaksi}/batal', [PesananController::class, 'batal'])->name('pesanan.batal');

            Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
            Route::get('/pelanggan/riwayat', [PelangganController::class, 'show'])->name('pelanggan.show');
        });

        Route::middleware('role:pegawai,super_admin')->group(function () {
            Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
            Route::patch('/ulasan/{ulasan}/toggle', [UlasanController::class, 'toggleAktif'])->name('ulasan.toggle');
            Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
        });
    });
});

Route::prefix('owner')->name('owner.')
    ->middleware([AdminMiddleware::class, 'role:owner,super_admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

// ============================================================
//  PEGAWAI — Dashboard operasional (/pegawai/...)
//  Semua rute dilindungi autentikasi (admin) + role di server.
// ============================================================
Route::prefix('pegawai')->name('pegawai.')
    ->middleware([AdminMiddleware::class, 'role:pegawai,super_admin'])
    ->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/konten', [KontenController::class, 'index'])->name('konten.index');
        Route::put('/konten/halaman', [KontenController::class, 'updateHalaman'])->name('konten.halaman');
        Route::put('/konten/info', [KontenController::class, 'updateInfo'])->name('konten.info');
        Route::put('/konten/tampilan', [KontenController::class, 'updateTampilan'])->name('konten.tampilan');
        Route::post('/banner', [BannerController::class, 'store'])->name('banner.store');
        Route::put('/banner/{banner}', [BannerController::class, 'update'])->name('banner.update');
        Route::delete('/banner/{banner}', [BannerController::class, 'destroy'])->name('banner.destroy');

        Route::get('/produk', [PegawaiProdukController::class, 'index'])->name('produk.index');
        Route::post('/produk', [PegawaiProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{produk}', [PegawaiProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [PegawaiProdukController::class, 'destroy'])->name('produk.destroy');
        Route::patch('/produk/{produk}/stok', [PegawaiProdukController::class, 'updateStok'])->name('produk.stok');
        Route::patch('/produk/{produk}/best-seller', [PegawaiProdukController::class, 'toggleBestSeller'])->name('produk.bestSeller');

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');

        Route::get('/transaksi', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi', [TransaksiController::class, 'update'])->name('transaksi.update');

        Route::get('/pesanan', [PegawaiPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{transaksi}', [PegawaiPesananController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{transaksi}/serahkan', [PegawaiPesananController::class, 'serahkan'])->name('pesanan.serahkan');
        Route::post('/pesanan/{transaksi}/tolak', [PegawaiPesananController::class, 'tolak'])->name('pesanan.tolak');
        Route::post('/pesanan/{transaksi}/verifikasi-qris', [PegawaiPesananController::class, 'verifikasiQris'])->name('pesanan.verifikasiQris');
        Route::post('/pesanan/{transaksi}/konfirmasi-tunai', [PegawaiPesananController::class, 'konfirmasiTunai'])->name('pesanan.konfirmasiTunai');

        Route::get('/log', [LogController::class, 'index'])->name('log.index');
    });
