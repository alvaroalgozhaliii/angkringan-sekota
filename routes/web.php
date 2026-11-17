<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\BannerController;
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

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kasir (POS)
        Route::get('/kasir',                       [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/transaksi',            [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/transaksi/{transaksi}', [KasirController::class, 'show'])->name('kasir.show');
        Route::get('/kasir/struk/{transaksi}',     [KasirController::class, 'struk'])->name('kasir.struk');
        Route::get('/kasir/riwayat',               [KasirController::class, 'riwayat'])->name('kasir.riwayat');

        // Banner Promo
        Route::get('/banner',                   [BannerController::class, 'index'])->name('banner.index');
        Route::get('/banner/tambah',            [BannerController::class, 'create'])->name('banner.create');
        Route::post('/banner',                  [BannerController::class, 'store'])->name('banner.store');
        Route::get('/banner/{banner}/ubah',     [BannerController::class, 'edit'])->name('banner.edit');
        Route::put('/banner/{banner}',          [BannerController::class, 'update'])->name('banner.update');
        Route::delete('/banner/{banner}',       [BannerController::class, 'destroy'])->name('banner.destroy');
        Route::patch('/banner/{banner}/toggle', [BannerController::class, 'toggleStatus'])->name('banner.toggle');

        // Profil & Kontak
        Route::get('/profil',         [ProfilController::class, 'index'])->name('profil.index');
        Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

        // Menu & Produk
        Route::get('/produk',                   [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/tambah',            [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk',                  [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/ubah',     [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}',          [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}',       [ProdukController::class, 'destroy'])->name('produk.destroy');
        Route::patch('/produk/{produk}/toggle', [ProdukController::class, 'toggleStatus'])->name('produk.toggle');

        // Pesan Masuk
        Route::get('/pesan',                    [PesanController::class, 'index'])->name('pesan.index');
        Route::get('/pesan/{pesan}',            [PesanController::class, 'show'])->name('pesan.show');
        Route::delete('/pesan/{pesan}',         [PesanController::class, 'destroy'])->name('pesan.destroy');
        Route::post('/pesan/mark-all-read',     [PesanController::class, 'markAllRead'])->name('pesan.markAllRead');

        // Ulasan Pengunjung
        Route::get('/ulasan',                   [UlasanController::class, 'index'])->name('ulasan.index');
        Route::patch('/ulasan/{ulasan}/toggle', [UlasanController::class, 'toggleAktif'])->name('ulasan.toggle');
        Route::delete('/ulasan/{ulasan}',       [UlasanController::class, 'destroy'])->name('ulasan.destroy');

        // Pengguna Admin (owner only)
        Route::get('/pengguna',                 [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna',                [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::delete('/pengguna/{user}',       [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    });
});
