<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengaturanTransaksiController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProfilAkunController;
use App\Http\Controllers\UlasanPublikController;
use App\Models\Banner;
use App\Models\Produk;
use App\Models\ProfilUsaha;
use App\Models\Testimonial;

// ============================================================
//  PUBLIC — Website Profil UMKM (pengunjung)
// ============================================================
Route::get('/', function () {
    $profil = ProfilUsaha::getSingleton();
    $produk = Produk::tayang()->get();
    $ulasan = Testimonial::aktif()->terbaru()->get();
    $banners = Banner::aktif()->get();
    return view('welcome', compact('profil', 'produk', 'ulasan', 'banners'));
})->name('home');

// Form kirim pesan dari web profil
Route::post('/kirim-pesan', [\App\Http\Controllers\PesanPublikController::class, 'store'])->name('pesan.store');

// Form kirim ulasan dari web profil
Route::post('/kirim-ulasan', [UlasanPublikController::class, 'store'])->name('ulasan.store');

// ============================================================
//  ADMIN — Panel Admin (/admin/...)
// ============================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (tidak perlu login)
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {

        Route::get('/profil-pengguna', [ProfilAkunController::class, 'edit'])->name('profil-akun.edit');
        Route::put('/profil-pengguna', [ProfilAkunController::class, 'update'])->name('profil-akun.update');

        Route::middleware('role:owner')->group(function () {
            Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
            Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
            Route::patch('/pengguna/{user}/role', [PenggunaController::class, 'updateRole'])->name('pengguna.role');
            Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

            Route::get('/pengaturan/transaksi', [PengaturanTransaksiController::class, 'edit'])->name('pengaturan.transaksi');
            Route::put('/pengaturan/transaksi', [PengaturanTransaksiController::class, 'update'])->name('pengaturan.transaksi.update');

            Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
            Route::get('/pesanan/{transaksi}/koreksi', [PesananController::class, 'edit'])->name('pesanan.edit');
            Route::put('/pesanan/{transaksi}', [PesananController::class, 'update'])->name('pesanan.update');
            Route::patch('/pesanan/{transaksi}/batal', [PesananController::class, 'batal'])->name('pesanan.batal');

            Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
            Route::get('/pelanggan/riwayat', [PelangganController::class, 'show'])->name('pelanggan.show');
        });

        Route::middleware('role:pegawai')->group(function () {
        // Profil & Kontak
        Route::get('/profil',         [ProfilController::class, 'index'])->name('profil.index');
        Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

        // Pesan Masuk
        Route::get('/pesan',                    [PesanController::class, 'index'])->name('pesan.index');
        Route::get('/pesan/{pesan}',            [PesanController::class, 'show'])->name('pesan.show');
        Route::delete('/pesan/{pesan}',         [PesanController::class, 'destroy'])->name('pesan.destroy');
        Route::post('/pesan/mark-all-read',     [PesanController::class, 'markAllRead'])->name('pesan.markAllRead');

        // Ulasan Pengunjung
        Route::get('/ulasan',                   [UlasanController::class, 'index'])->name('ulasan.index');
        Route::patch('/ulasan/{ulasan}/toggle', [UlasanController::class, 'toggleAktif'])->name('ulasan.toggle');
        Route::delete('/ulasan/{ulasan}',       [UlasanController::class, 'destroy'])->name('ulasan.destroy');
        });
    });
});
