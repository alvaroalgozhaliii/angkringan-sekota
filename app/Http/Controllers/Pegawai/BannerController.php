<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Banner;
use App\Support\ActivityLogger;
use App\Support\UploadGambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends PegawaiController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:1000',
            'urutan' => 'nullable|integer|min:0|max:999',
            'is_aktif' => 'nullable|boolean',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:'.UploadGambar::UKURAN_MAKSIMAL,
        ], [
            'gambar.required' => 'Gambar banner wajib diunggah.',
            'gambar.image' => 'Gambar banner harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $path = UploadGambar::simpan($request->file('gambar'), 'banner');
        $data['gambar'] = $path;
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_aktif'] = $request->boolean('is_aktif');

        $banner = DB::transaction(function () use ($data) {
            $banner = Banner::create($data);
            ActivityLogger::log('Tampilan Web', 'Tambah banner', null, [
                'judul' => $banner->judul ?: '-',
                'gambar' => $banner->gambar,
                'is_aktif' => $banner->is_aktif ? 'ya' : 'tidak',
            ], 'banner', $banner->id);

            return $banner;
        });

        return back()->with('success', 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:1000',
            'urutan' => 'nullable|integer|min:0|max:999',
            'is_aktif' => 'nullable|boolean',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:'.UploadGambar::UKURAN_MAKSIMAL,
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_aktif'] = $request->boolean('is_aktif');

        $sebelum = [
            'judul' => $banner->judul ?: '-',
            'gambar' => $banner->gambar,
            'urutan' => $banner->urutan,
            'is_aktif' => $banner->is_aktif ? 'ya' : 'tidak',
        ];

        if ($request->hasFile('gambar')) {
            $gambarLama = $banner->gambar;
            $data['gambar'] = UploadGambar::simpan($request->file('gambar'), 'banner');
        }

        DB::transaction(function () use ($banner, $data, $sebelum) {
            $banner->update($data);
            ActivityLogger::log('Tampilan Web', 'Ubah banner', $sebelum, [
                'judul' => $banner->judul ?: '-',
                'gambar' => $banner->gambar,
                'urutan' => $banner->urutan,
                'is_aktif' => $banner->is_aktif ? 'ya' : 'tidak',
            ], 'banner', $banner->id);
        });

        if (isset($gambarLama)) {
            UploadGambar::hapus($gambarLama);
        }

        return back()->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        $gambar = $banner->gambar;
        DB::transaction(function () use ($banner) {
            ActivityLogger::log('Tampilan Web', 'Hapus banner', [
                'judul' => $banner->judul ?: '-',
                'gambar' => $banner->gambar,
            ], null, 'banner', $banner->id);
            $banner->delete();
        });
        UploadGambar::hapus($gambar);

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
