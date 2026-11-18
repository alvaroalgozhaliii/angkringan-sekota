<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Produk;
use App\Services\PesananService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KasirController extends PegawaiController
{
    public function index()
    {
        $produk = Produk::tayang()->orderBy('nama')->get();

        return view('pegawai.kasir.index', compact('produk'));
    }

    public function store(Request $request, PesananService $service)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'nullable|string|max:100',
            'nomor_wa' => 'nullable|string|max:30',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|string',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'uang_diterima' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Pilih minimal satu menu.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'uang_diterima.required' => 'Nominal uang diterima wajib diisi.',
        ]);

        $items = json_decode($data['items'], true);
        if (! is_array($items) || $items === []) {
            throw ValidationException::withMessages(['items' => 'Pilih minimal satu menu.']);
        }

        $ringkasan = collect();
        foreach ($items as $item) {
            $produk = Produk::find((int) ($item['id'] ?? 0));
            $qty = (int) ($item['qty'] ?? 0);
            if (! $produk || $qty < 1 || $qty > 999) {
                throw ValidationException::withMessages(['items' => 'Menu yang dipilih tidak valid.']);
            }
            if ($produk->stok < $qty) {
                throw ValidationException::withMessages(['items' => 'Stok '.$produk->nama.' tidak mencukupi (tersisa '.$produk->stok.').']);
            }
            $ringkasan->push([
                'produk' => $produk,
                'qty' => $qty,
                'subtotal' => (float) $produk->harga * $qty,
            ]);
        }

        if ($ringkasan->isEmpty()) {
            throw ValidationException::withMessages(['items' => 'Pilih minimal satu menu.']);
        }

        $total = (float) $ringkasan->sum('subtotal');
        $uangDiterima = $data['metode_pembayaran'] === 'qris' ? $total : (float) $data['uang_diterima'];
        if ($uangDiterima < $total) {
            throw ValidationException::withMessages([
                'uang_diterima' => 'Uang diterima kurang dari total belanja (Rp '.number_format($total, 0, ',', '.').').',
            ]);
        }

        try {
            $transaksi = $service->catatPesananKasir($data, $ringkasan, $total, $uangDiterima);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first())->withInput();
        }

        return redirect()->route('pegawai.kasir.index')
            ->with('success', 'Pesanan kasir '.$transaksi->nomor_transaksi.' berhasil dicatat. Stok otomatis berkurang.');
    }
}
