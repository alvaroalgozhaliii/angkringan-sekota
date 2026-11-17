<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanTransaksi;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['details', 'user']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_transaksi', 'like', "%{$keyword}%")
                    ->orWhere('nama_kasir', 'like', "%{$keyword}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['selesai', 'batal'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->where('created_at', '>=', Carbon::parse($request->tanggal_mulai)->startOfDay());
        }
        if ($request->filled('tanggal_akhir')) {
            $query->where('created_at', '<=', Carbon::parse($request->tanggal_akhir)->endOfDay());
        }

        $pesanan = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details');
        $pengaturan = PengaturanTransaksi::current();
        $metode = $pengaturan->metodeDiizinkan();
        if (!in_array($transaksi->metode_pembayaran, $metode, true)) {
            $metode[] = $transaksi->metode_pembayaran;
        }

        return view('admin.pesanan.edit', compact('transaksi', 'metode'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $pengaturan = PengaturanTransaksi::current();
        $metode = $pengaturan->metodeDiizinkan();
        if (!in_array($transaksi->metode_pembayaran, $metode, true)) {
            $metode[] = $transaksi->metode_pembayaran;
        }

        $data = $request->validate([
            'nama_pelanggan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'metode_pembayaran' => 'required|in:' . implode(',', $metode),
            'status' => 'required|in:selesai,batal',
            'uang_diterima' => 'nullable|numeric|min:0',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1|max:999',
        ]);

        $transaksi->load('details');

        $total = 0;
        foreach ($transaksi->details as $detail) {
            if (!array_key_exists($detail->id, $data['jumlah'])) {
                return back()->with('error', 'Item pesanan tidak lengkap.')->withInput();
            }
            $total += (float) $detail->harga_satuan * (int) $data['jumlah'][$detail->id];
        }

        $uang = 0;
        $kembalian = 0;
        if ($data['metode_pembayaran'] === 'qris') {
            $uang = $total;
        } else {
            $uang = isset($data['uang_diterima']) ? (float) $data['uang_diterima'] : 0;
            if ($data['status'] === 'selesai' && $uang < $total) {
                return back()->with('error', 'Uang tunai kurang dari total pesanan.')->withInput();
            }
            $kembalian = max(0, $uang - $total);
        }

        DB::transaction(function () use ($transaksi, $data, $uang, $kembalian) {
            $total = 0;
            foreach ($transaksi->details as $detail) {
                $jumlah = (int) $data['jumlah'][$detail->id];
                $subtotal = (float) $detail->harga_satuan * $jumlah;
                $detail->update([
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $transaksi->update([
                'nama_pelanggan' => $data['nama_pelanggan'] ?: null,
                'catatan' => $data['catatan'] ?: null,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'status' => $data['status'],
                'total_harga' => $total,
                'uang_diterima' => $uang,
                'kembalian' => $kembalian,
            ]);
        });

        return redirect()
            ->route('admin.pesanan.index')
            ->with('success', 'Pesanan ' . $transaksi->nomor_transaksi . ' diperbarui.');
    }

    public function batal(Transaksi $transaksi)
    {
        if ($transaksi->status === 'batal') {
            return back()->with('error', 'Pesanan ini sudah dibatalkan.');
        }

        $transaksi->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan ' . $transaksi->nomor_transaksi . ' dibatalkan.');
    }
}
