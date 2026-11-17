<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::query()
            ->whereNotNull('nama_pelanggan')
            ->where('nama_pelanggan', '!=', '');

        if ($request->filled('q')) {
            $query->where('nama_pelanggan', 'like', '%' . trim($request->q) . '%');
        }

        $pelanggan = $query
            ->select([
                'nama_pelanggan',
                DB::raw('COUNT(*) as jumlah_pesanan'),
                DB::raw("SUM(CASE WHEN status = 'selesai' THEN total_harga ELSE 0 END) as total_belanja"),
                DB::raw('MAX(created_at) as terakhir'),
            ])
            ->groupBy('nama_pelanggan')
            ->orderByDesc('terakhir')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function show(Request $request)
    {
        $nama = trim((string) $request->query('nama', ''));
        if ($nama === '') {
            return redirect()->route('admin.pelanggan.index');
        }

        $pesanan = Transaksi::with('details')
            ->where('nama_pelanggan', $nama)
            ->orderByDesc('id')
            ->get();

        if ($pesanan->isEmpty()) {
            return redirect()
                ->route('admin.pelanggan.index')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        return view('admin.pelanggan.show', compact('nama', 'pesanan'));
    }
}
