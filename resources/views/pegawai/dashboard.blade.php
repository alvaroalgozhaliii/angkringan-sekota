@extends('admin.layouts.app')
@section('page-title', 'Dashboard')
@section('title', 'Dashboard Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Halo, {{ auth()->user()->name }}</h2>
        <div class="page-sub">Ringkasan operasional dan pesanan Angkringan Sekota hari ini.</div>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <a href="{{ route('pegawai.kasir.index') }}" class="btn-sm btn-red">Buka Kasir</a>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-white">Lihat web publik</a>
    </div>
</div>

@include('partials.ringkasan-dashboard', ['ruteAntrian' => 'pegawai.pesanan.index'])

<div class="two-col" style="margin-bottom:1.5rem">
    <div class="panel">
        <h2 class="panel-title">Aksi Cepat Operasional</h2>
        <div class="btn-row">
            <a href="{{ route('pegawai.kasir.index') }}" class="btn-sm btn-lg btn-red">Buka Kasir</a>
            <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-lg btn-white">Pesanan dan Pembayaran</a>
            <a href="{{ route('pegawai.produk.index') }}" class="btn-sm btn-lg btn-white">Produk dan Stok</a>
            <a href="{{ route('pegawai.konten.index') }}" class="btn-sm btn-lg btn-white">Tampilan Website</a>
        </div>
        <div class="divider"></div>
        <div class="hint">Transaksi kasir hari ini: {{ $transaksiHariIniCount }}. Ketuk menu di atas untuk mengelola transaksi, pesanan pelanggan, atau stok menu.</div>
    </div>

    <div class="panel">
        <div class="panel-title-row">
            <h2 class="panel-title" style="margin-bottom:0;border:none;padding-bottom:0">Peringatan Stok Menipis ({{ $stokMenipisCount }})</h2>
            <a href="{{ route('pegawai.produk.index', ['stok_menipis' => 1]) }}" class="action-link">Lihat semua</a>
        </div>
        @if($produkStokMenipis->isEmpty())
            <div class="hint">Semua stok produk dalam kondisi aman (lebih dari 5).</div>
        @else
            <div style="display:flex;flex-direction:column;gap:.5rem">
                @foreach($produkStokMenipis as $p)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--border)">
                        <div>
                            <strong style="font-size:.85rem">{{ $p->nama }}</strong>
                            <div class="hint">{{ $p->kategori }} • {{ $p->harga_format }}</div>
                        </div>
                        <span class="badge badge-{{ $p->stok <= 0 ? 'red' : 'yellow' }}">
                            {{ $p->stok <= 0 ? 'Habis (0)' : 'Sisa '.$p->stok }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="panel">
    <div class="panel-title-row">
        <h2 class="panel-title" style="margin-bottom:0;border:none;padding-bottom:0">Pesanan Masuk Terbaru</h2>
        <a href="{{ route('pegawai.pesanan.index') }}" class="action-link">Lihat semua pesanan</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Waktu</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru as $item)
                    <tr>
                        <td><strong>{{ $item->nomor_transaksi }}</strong></td>
                        <td style="white-space:nowrap">{{ $item->formatted_tanggal_waktu }}</td>
                        <td>
                            {{ $item->nama_pelanggan ?: 'Walk-in' }}
                            @if($item->no_telepon_pelanggan)<div class="hint">{{ $item->no_telepon_pelanggan }}</div>@endif
                        </td>
                        <td>{{ $item->formatted_total }}</td>
                        <td><span class="badge badge-{{ \App\Support\OrderStatus::badge($item->status) }}">{{ $item->status_label }}</span></td>
                        <td>
                            <a href="{{ route('pegawai.pesanan.show', $item) }}" class="action-link">Detail / Proses</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--muted);padding:1.5rem">Belum ada pesanan masuk. Buka Kasir untuk membuat transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
