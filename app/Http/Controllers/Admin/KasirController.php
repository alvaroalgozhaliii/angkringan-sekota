<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProfilUsaha;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Tampilkan halaman utama Kasir (POS)
     */
    public function index()
    {
        $profil = ProfilUsaha::getSingleton();

        // Ambil produk yang tayang
        $produk = Produk::where('status', 'tayang')
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Kategori dinamis dari database produk
        $categoriesFromDb = Produk::where('status', 'tayang')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->values()
            ->toArray();

        $defaultCategories = ['Makanan', 'Minuman', 'Camilan'];
        $categories = array_values(array_unique(array_merge($defaultCategories, $categoriesFromDb)));

        // Riwayat transaksi hari ini (10 terakhir)
        $transaksiHariIni = Transaksi::hariIni()
            ->with('details')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $totalPendapatanHariIni = Transaksi::hariIni()
            ->where('status', 'selesai')
            ->sum('total_harga');

        $totalTransaksiHariIni = Transaksi::hariIni()
            ->where('status', 'selesai')
            ->count();

        return view('admin.kasir.index', compact(
            'profil',
            'produk',
            'categories',
            'transaksiHariIni',
            'totalPendapatanHariIni',
            'totalTransaksiHariIni'
        ));
    }

    /**
     * Proses simpan transaksi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.id'        => 'required|integer|exists:produk,id',
            'items.*.qty'       => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'uang_diterima'     => 'nullable|numeric|min:0',
            'nama_pelanggan'    => 'nullable|string|max:100',
            'catatan'           => 'nullable|string|max:500',
        ], [
            'items.required'            => 'Keranjang pesanan masih kosong.',
            'items.min'                 => 'Pilih minimal satu menu produk.',
            'metode_pembayaran.required'=> 'Pilih metode pembayaran (Tunai atau QRIS).',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total dari database untuk mencegah manipulasi harga dari client
            $totalHarga = 0;
            $itemsData = [];

            foreach ($validated['items'] as $itemInput) {
                $produk = Produk::lockForUpdate()->find($itemInput['id']);
                if (!$produk) {
                    throw new \Exception("Produk ID {$itemInput['id']} tidak ditemukan.");
                }

                $qty = (int) $itemInput['qty'];
                $hargaSatuan = (float) $produk->harga;
                $subtotal = $hargaSatuan * $qty;
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'produk_id'    => $produk->id,
                    'nama_produk'  => $produk->nama,
                    'kategori'     => $produk->kategori,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah'       => $qty,
                    'subtotal'     => $subtotal,
                ];
            }

            $metode = $validated['metode_pembayaran'];
            $uangDiterima = 0;
            $kembalian = 0;

            if ($metode === 'tunai') {
                $uangDiterima = isset($validated['uang_diterima']) ? (float) $validated['uang_diterima'] : 0;
                if ($uangDiterima < $totalHarga) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uang tunai yang diterima kurang Rp ' . number_format($totalHarga - $uangDiterima, 0, ',', '.') . '.',
                    ], 422);
                }
                $kembalian = $uangDiterima - $totalHarga;
            } else {
                // QRIS: uang pas sesuai nominal transaksi
                $uangDiterima = $totalHarga;
                $kembalian = 0;
            }

            $nomorTransaksi = Transaksi::generateNomorTransaksi();

            $transaksi = Transaksi::create([
                'nomor_transaksi'   => $nomorTransaksi,
                'user_id'           => Auth::id(),
                'nama_kasir'        => Auth::user() ? Auth::user()->name : 'Kasir',
                'nama_pelanggan'    => $validated['nama_pelanggan'] ?? null,
                'catatan'           => $validated['catatan'] ?? null,
                'total_harga'       => $totalHarga,
                'metode_pembayaran' => $metode,
                'uang_diterima'     => $uangDiterima,
                'kembalian'         => $kembalian,
                'status'            => 'selesai',
            ]);

            foreach ($itemsData as $item) {
                $item['transaksi_id'] = $transaksi->id;
                TransaksiDetail::create($item);
            }

            DB::commit();

            // Load relasi untuk respon JSON
            $transaksi->load('details');

            return response()->json([
                'success'   => true,
                'message'   => 'Transaksi berhasil disimpan!',
                'transaksi' => [
                    'id'               => $transaksi->id,
                    'nomor_transaksi'  => $transaksi->nomor_transaksi,
                    'waktu'            => $transaksi->formatted_waktu,
                    'tanggal_waktu'    => $transaksi->formatted_tanggal_waktu,
                    'nama_kasir'       => $transaksi->nama_kasir,
                    'nama_pelanggan'   => $transaksi->nama_pelanggan,
                    'total_harga'      => $transaksi->total_harga,
                    'total_format'     => $transaksi->formatted_total,
                    'metode_pembayaran'=> strtoupper($transaksi->metode_pembayaran),
                    'uang_diterima'    => $transaksi->uang_diterima,
                    'uang_diterima_fmt'=> $transaksi->formatted_uang_diterima,
                    'kembalian'        => $transaksi->kembalian,
                    'kembalian_fmt'    => $transaksi->formatted_kembalian,
                    'details'          => $transaksi->details->map(function ($d) {
                        return [
                            'nama'         => $d->nama_produk,
                            'jumlah'       => $d->jumlah,
                            'harga_satuan' => $d->harga_satuan,
                            'harga_fmt'    => $d->harga_format,
                            'subtotal'     => $d->subtotal,
                            'subtotal_fmt' => $d->subtotal_format,
                        ];
                    }),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil data transaksi spesifik (untuk popup detail)
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['details', 'user']);

        return response()->json([
            'id'                => $transaksi->id,
            'nomor_transaksi'   => $transaksi->nomor_transaksi,
            'waktu'             => $transaksi->formatted_waktu,
            'tanggal_waktu'     => $transaksi->formatted_tanggal_waktu,
            'nama_kasir'        => $transaksi->nama_kasir,
            'nama_pelanggan'    => $transaksi->nama_pelanggan,
            'catatan'           => $transaksi->catatan,
            'total_harga'       => $transaksi->total_harga,
            'total_format'      => $transaksi->formatted_total,
            'metode_pembayaran' => strtoupper($transaksi->metode_pembayaran),
            'uang_diterima'     => $transaksi->uang_diterima,
            'uang_diterima_fmt' => $transaksi->formatted_uang_diterima,
            'kembalian'         => $transaksi->kembalian,
            'kembalian_fmt'     => $transaksi->formatted_kembalian,
            'status'            => $transaksi->status,
            'details'           => $transaksi->details->map(function ($d) {
                return [
                    'nama'         => $d->nama_produk,
                    'jumlah'       => $d->jumlah,
                    'harga_satuan' => $d->harga_satuan,
                    'harga_fmt'    => $d->harga_format,
                    'subtotal'     => $d->subtotal,
                    'subtotal_fmt' => $d->subtotal_format,
                ];
            }),
        ]);
    }

    /**
     * Halaman cetak struk thermal
     */
    public function struk(Transaksi $transaksi)
    {
        $transaksi->load(['details', 'user']);
        $profil = ProfilUsaha::getSingleton();

        return view('admin.kasir.struk', compact('transaksi', 'profil'));
    }

    /**
     * Halaman riwayat transaksi lengkap dengan filter
     */
    public function riwayat(Request $request)
    {
        $profil = ProfilUsaha::getSingleton();

        $query = Transaksi::with(['details', 'user']);

        // Filter nomor transaksi
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('nama_kasir', 'like', "%{$keyword}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$keyword}%");
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        } elseif ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_akhir)->endOfDay(),
            ]);
        }

        // Filter metode pembayaran
        if ($request->filled('metode') && in_array($request->metode, ['tunai', 'qris'])) {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Clone query untuk kalkulasi total statistik sebelum pagination
        $statsQuery = clone $query;
        $totalPendapatan = (float) $statsQuery->where('status', 'selesai')->sum('total_harga');
        $totalTransaksi  = $statsQuery->count();
        $totalTunai      = (float) (clone $query)->where('status', 'selesai')->where('metode_pembayaran', 'tunai')->sum('total_harga');
        $totalQris       = (float) (clone $query)->where('status', 'selesai')->where('metode_pembayaran', 'qris')->sum('total_harga');

        $transaksiList = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.kasir.riwayat', compact(
            'profil',
            'transaksiList',
            'totalPendapatan',
            'totalTransaksi',
            'totalTunai',
            'totalQris'
        ));
    }
}
