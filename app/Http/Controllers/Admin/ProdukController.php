<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('urutan')->orderBy('id')->get();
        return view('admin.produk.index', compact('produk'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'    => 'required|in:tayang,tidak_tayang',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['nama','kategori','harga','deskripsi','status','urutan']);
        $data['urutan'] = $data['urutan'] ?? 0;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
            $data['gambar'] = $path;
        }

        Produk::create($data);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'    => 'required|in:tayang,tidak_tayang',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['nama','kategori','harga','deskripsi','status','urutan']);
        $data['urutan'] = $data['urutan'] ?? 0;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $path = $request->file('gambar')->store('produk', 'public');
            $data['gambar'] = $path;
        }

        $produk->update($data);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleStatus(Produk $produk)
    {
        $produk->update([
            'status' => $produk->status === 'tayang' ? 'tidak_tayang' : 'tayang'
        ]);
        return back()->with('success', 'Status produk diperbarui!');
    }
}
