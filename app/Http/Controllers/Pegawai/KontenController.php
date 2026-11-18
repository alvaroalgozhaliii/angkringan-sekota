<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Banner;
use App\Models\ProfilUsaha;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontenController extends PegawaiController
{
    public function index()
    {
        return view('pegawai.konten.index', [
            'profil' => ProfilUsaha::getSingleton(),
            'profilBanners' => Banner::orderBy('urutan')->orderByDesc('id')->get(),
        ]);
    }

    public function updateHalaman(Request $request)
    {
        $data = $request->validate([
            'judul_beranda' => 'nullable|string|max:255',
            'deskripsi_beranda' => 'nullable|string|max:1000',
            'teks_tentang_kami' => 'nullable|string|max:3000',
            'visi' => 'nullable|string|max:2000',
            'misi' => 'nullable|string|max:3000',
        ], [
            'judul_beranda.max' => 'Judul beranda maksimal 255 karakter.',
            'deskripsi_beranda.max' => 'Deskripsi beranda maksimal 1000 karakter.',
        ]);

        $this->simpan($data, 'Ubah konten halaman', [
            'judul_beranda', 'deskripsi_beranda', 'teks_tentang_kami', 'visi', 'misi',
        ]);

        return back()->with('success', 'Konten halaman berhasil diperbarui. Perubahan langsung tampil di website publik.');
    }

    public function updateInfo(Request $request)
    {
        $data = $request->validate([
            'alamat' => 'nullable|string|max:500',
            'maps_link' => 'nullable|url|max:1000',
            'jam_buka' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'facebook' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
        ], [
            'maps_link.url' => 'Tautan peta harus berupa URL yang valid.',
        ]);

        $this->simpan($data, 'Ubah info operasional', [
            'alamat', 'maps_link', 'jam_buka', 'instagram', 'facebook', 'tiktok',
        ]);

        return back()->with('success', 'Jam operasional, lokasi, dan sosial media berhasil diperbarui.');
    }

    public function updateTampilan(Request $request)
    {
        $data = $request->validate([
            'warna_tema' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'tampilkan_banner' => 'nullable|boolean',
        ], [
            'warna_tema.required' => 'Warna tema wajib dipilih.',
            'warna_tema.regex' => 'Warna tema harus berupa kode hex, misalnya #C8102E.',
        ]);
        $data['tampilkan_banner'] = $request->boolean('tampilkan_banner');

        $this->simpan($data, 'Ubah tampilan website', ['warna_tema', 'tampilkan_banner']);

        return back()->with('success', 'Tema dan pengaturan banner berhasil disimpan.');
    }

    private function simpan(array $data, string $aksi, array $field): void
    {
        $profil = ProfilUsaha::getSingleton();
        $sebelum = [];
        $sesudah = [];
        foreach ($field as $key) {
            $sebelum[$key] = $profil->{$key} ?: '-';
            $sesudah[$key] = $data[$key] ?? '-';
        }

        DB::transaction(function () use ($profil, $data, $aksi, $sebelum, $sesudah) {
            $profil->update($data);
            ActivityLogger::log('Tampilan Web', $aksi, $sebelum, $sesudah, 'profil_usaha', $profil->id);
        });
    }
}
