@extends('admin.layouts.app')
@section('page-title', 'Pesanan & Pembayaran')
@section('title', 'Pesanan & Pembayaran')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pesanan &amp; Pembayaran</div>
        <div class="page-sub">Verifikasi pembayaran, ubah status, dan pantau pesanan pelanggan.</div>
    </div>
</div>

@if(is_string(request('grup')) && \App\Support\OrderStatus::labelGrupAntrean(request('grup')))
    <div class="filter-notice">
        Menampilkan antrian: <strong>{{ \App\Support\OrderStatus::labelGrupAntrean(request('grup')) }}</strong>.
        <a href="{{ route('pegawai.pesanan.index') }}" class="action-link">Hapus filter</a>
    </div>
@endif

<div class="panel">
    <form method="GET" action="{{ route('pegawai.pesanan.index') }}" class="filter-grid">
        <div class="form-group">
            <label class="form-label">Cari</label>
            <input name="q" class="form-control" value="{{ request('q') }}" placeholder="Nomor / nama / WhatsApp">
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                @foreach($statusList as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Metode bayar</label>
            <select name="metode" class="form-control">
                <option value="">Semua metode</option>
                <option value="tunai" {{ request('metode') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="qris" {{ request('metode') === 'qris' ? 'selected' : '' }}>QRIS</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Sumber</label>
            <select name="sumber" class="form-control">
                <option value="">Semua sumber</option>
                <option value="website" {{ request('sumber') === 'website' ? 'selected' : '' }}>Online (website)</option>
                <option value="kasir" {{ request('sumber') === 'kasir' ? 'selected' : '' }}>Kasir</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Dari tanggal</label>
            <input name="tanggal_mulai" type="date" class="form-control" value="{{ request('tanggal_mulai') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Sampai tanggal</label>
            <input name="tanggal_akhir" type="date" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>
        <div style="grid-column:1/-1;display:flex;gap:.5rem">
            <button class="btn-sm btn-red">Filter</button>
            <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-white">Reset</a>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-title">Daftar Pesanan ({{ $pesanan->total() }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nomor</th><th>Sumber</th><th>Pelanggan</th><th>Waktu</th><th>Metode</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pesanan as $item)
                    <tr>
                        <td><strong>{{ $item->nomor_transaksi }}</strong></td>
                        <td><span class="badge badge-{{ $item->sumber === 'website' ? 'purple' : 'gray' }}">{{ $item->sumber_label }}</span></td>
                        <td>
                            {{ $item->nama_pelanggan ?: '-' }}
                            @if($item->no_telepon_pelanggan)<div class="hint">{{ $item->no_telepon_pelanggan }}</div>@endif
                        </td>
                        <td style="white-space:nowrap">{{ $item->formatted_tanggal_waktu }}</td>
                        <td>{{ strtoupper($item->metode_pembayaran) }}<div class="hint">{{ $item->metode_pengambilan === 'pengiriman' ? 'Dikirim' : 'Ambil di toko' }}</div></td>
                        <td>{{ $item->formatted_total }}</td>
                        <td><span class="badge badge-{{ \App\Support\OrderStatus::badge($item->status) }}">{{ $item->status_label }}</span></td>
                        <td>
                            <a href="{{ route('pegawai.pesanan.show', $item) }}" class="action-link">Detail / Proses</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--muted)">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $pesanan->links() }}</div>
</div>
@endsection
