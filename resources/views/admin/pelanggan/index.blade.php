@extends('admin.layouts.app')
@section('page-title', 'Data pelanggan')
@section('title', 'Data Pelanggan')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Data pelanggan</h2>
        <div class="page-sub">Pelanggan dikelompokkan dari nama yang tercatat di transaksi.</div>
    </div>
</div>

<div class="panel">
    <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="filter-grid" style="margin-bottom:1rem">
        <div class="form-group">
            <label class="form-label" for="q">Cari nama</label>
            <input class="form-control" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Nama pelanggan">
        </div>
        <div class="form-group">
            <button type="submit" class="btn-sm btn-red" style="min-height:42px">Cari</button>
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah pesanan</th>
                    <th>Total belanja</th>
                    <th>Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggan as $item)
                    <tr>
                        <td><strong>{{ $item->nama_pelanggan }}</strong></td>
                        <td>{{ $item->jumlah_pesanan }}</td>
                        <td>Rp{{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                        <td style="white-space:nowrap">{{ $item->terakhir ? \Illuminate\Support\Carbon::parse($item->terakhir)->translatedFormat('d M Y, H:i') : '-' }}</td>
                        <td><a href="{{ route('admin.pelanggan.show', ['nama' => $item->nama_pelanggan]) }}" class="action-link">Lihat riwayat</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--muted);padding:1.5rem">Belum ada pelanggan dengan nama tercatat. Transaksi kasir tanpa nama tidak muncul di daftar ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $pelanggan->links() }}</div>
</div>
@endsection
