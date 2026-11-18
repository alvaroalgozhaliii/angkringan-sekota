<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Produk;
use App\Support\ActivityLogger;
use App\Support\UploadGambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends PegawaiController
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('kategori', 'like', "%{$keyword}%");
            });
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', 'like', '%'.trim($request->kategori).'%');
        }
        if ($request->filled('status') && in_array($request->status, ['tayang', 'aktif', 'nonaktif'], true)) {
            $query->where('status', $request->status);
        }
        if ($request->boolean('stok_menipis')) {
            $query->where('stok', '<=', 5);
        }

        $produk = $query->orderBy('urutan')->orderBy('id')->paginate(15)->withQueryString();
        $edit = null;
        if ($request->filled('edit')) {
            $edit = Produk::find($request->integer('edit'));
        }

        return view('pegawai.produk.index', compact('produk', 'edit'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['gambar'] = $request->hasFile('gambar')
            ? UploadGambar::simpan($request->file('gambar'), 'produk')
            : null;

        $produk = DB::transaction(function () use ($data) {
            $produk = Produk::create($data);
            ActivityLogger::log('Produk & Stok', 'Tambah produk', null, $this->ringkas($produk), 'produk', $produk->id);

            return $produk;
        });

        return back()->with('success', 'Produk '.$produk->nama.' berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $this->validasi($request, $produk->id);
        $sebelum = $this->ringkas($produk);
        $gambarLama = null;

        if ($request->hasFile('gambar')) {
            $gambarLama = $produk->gambar;
            $data['gambar'] = UploadGambar::simpan($request->file('gambar'), 'produk');
        }

        DB::transaction(function () use ($produk, $data, $sebelum) {
            $produk->update($data);
            ActivityLogger::log('Produk & Stok', 'Ubah produk', $sebelum, $this->ringkas($produk->fresh()), 'produk', $produk->id);
        });

        if ($gambarLama) {
            UploadGambar::hapus($gambarLama);
        }

        return redirect()->route('pegawai.produk.index')->with('success', 'Produk '.$produk->nama.' berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $gambar = $produk->gambar;
        DB::transaction(function () use ($produk) {
            ActivityLogger::log('Produk & Stok', 'Hapus produk', $this->ringkas($produk), null, 'produk', $produk->id);
            $produk->delete();
        });
        UploadGambar::hapus($gambar);

        return back()->with('success', 'Produk '.$produk->nama.' berhasil dihapus.');
    }

    public function updateStok(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'stok' => 'required|integer|min:0|max:999999',
        ], [
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($produk, $data) {
            $sebelum = ['stok' => $produk->stok];
            $produk->update(['stok' => $data['stok']]);
            ActivityLogger::log('Produk & Stok', 'Ubah stok produk', $sebelum, ['stok' => $produk->stok], 'produk', $produk->id);
        });

        return back()->with('success', 'Stok '.$produk->nama.' diperbarui menjadi '.$produk->stok.'.');
    }

    public function toggleBestSeller(Produk $produk)
    {
        DB::transaction(function () use ($produk) {
            $sebelum = ['best_seller' => $produk->is_best_seller ? 'ya' : 'tidak'];
            $produk->update(['is_best_seller' => ! $produk->is_best_seller]);
            ActivityLogger::log('Produk & Stok', 'Ubah tanda best seller', $sebelum, [
                'best_seller' => $produk->is_best_seller ? 'ya' : 'tidak',
            ], 'produk', $produk->id);
        });

        return back()->with('success', 'Tanda best seller berhasil diubah.');
    }

    private function validasi(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'harga' => 'required|numeric|min:0|max:999999999',
            'stok' => 'required|integer|min:0|max:999999',
            'status' => 'required|in:tayang,aktif,nonaktif',
            'urutan' => 'nullable|integer|min:0|max:999',
            'deskripsi' => 'nullable|string|max:2000',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:'.UploadGambar::UKURAN_MAKSIMAL,
        ], [
            'nama.required' => 'Nama produk wajib diisi.',
            'kategori.max' => 'Kategori maksimal 100 karakter.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'status.in' => 'Status produk tidak valid.',
            'gambar.image' => 'Gambar harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;
        $data['kategori'] = trim((string) ($data['kategori'] ?? '')) ?: 'Lainnya';

        return $data;
    }

    private function ringkas(Produk $produk): array
    {
        return [
            'nama' => $produk->nama,
            'kategori' => $produk->kategori,
            'harga' => $produk->harga,
            'stok' => $produk->stok,
            'status' => $produk->status,
            'best_seller' => $produk->is_best_seller ? 'ya' : 'tidak',
            'gambar' => $produk->gambar ?: '-',
        ];
    }
}
