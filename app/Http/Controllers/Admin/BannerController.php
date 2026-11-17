<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('urutan')->orderBy('id', 'desc')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'link'      => 'nullable|string|max:255',
            'urutan'    => 'nullable|integer|min:0',
            'is_aktif'  => 'nullable|boolean',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $data = [
            'judul'     => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'),
            'link'      => $request->input('link'),
            'urutan'    => $request->input('urutan', 0) ?? 0,
            'is_aktif'  => $request->has('is_aktif') ? 1 : 0,
        ];

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('banners', 'public');
            $data['gambar'] = $path;
        }

        Banner::create($data);

        return redirect()->route('admin.banner.index')->with('success', 'Banner baru berhasil ditambahkan!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'link'      => 'nullable|string|max:255',
            'urutan'    => 'nullable|integer|min:0',
            'is_aktif'  => 'nullable|boolean',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $data = [
            'judul'     => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'),
            'link'      => $request->input('link'),
            'urutan'    => $request->input('urutan', 0) ?? 0,
            'is_aktif'  => $request->has('is_aktif') ? 1 : 0,
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika tersimpan di disk public
            if ($banner->gambar && !str_starts_with($banner->gambar, 'images/') && !str_starts_with($banner->gambar, 'http')) {
                Storage::disk('public')->delete($banner->gambar);
            }
            $path = $request->file('gambar')->store('banners', 'public');
            $data['gambar'] = $path;
        }

        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->gambar && !str_starts_with($banner->gambar, 'images/') && !str_starts_with($banner->gambar, 'http')) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil dihapus!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update([
            'is_aktif' => !$banner->is_aktif,
        ]);

        return back()->with('success', 'Status banner berhasil diubah!');
    }
}
