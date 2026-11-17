@extends('admin.layouts.app')
@section('page-title', 'Pantau pesanan')
@section('title', 'Pantau pesanan')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pantau pesanan</div>
        <div class="page-sub">Semua pesanan kasir. Batalkan atau koreksi jika ada yang salah.</div>
    </div>
</div>

<div class="panel">
    <form method="GET" action="{{ route('admin.pesanan.index') }}">
        <div class="order-filters" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:.75rem;align-items:flex-end">
            <div>
                <label class="form-label" for="q">Cari</label>
                <input id="q" type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nomor, kasir, atau pelanggan">
            </div>
            <div>
                <label class="form-label" for="tanggal_mulai">Dari</label>
                <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" for="tanggal_akhir">Sampai</label>
                <input id="tanggal_akhir" type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Semua</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <button type="submit" class="btn-sm btn-red">Filter</button>
        </div>
    </form>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $t)
                <tr>
                    <td><strong>{{ $t->nomor_transaksi }}</strong></td>
                    <td>{{ $t->formatted_tanggal_waktu }}</td>
                    <td>{{ $t->nama_kasir }}</td>
                    <td>{{ $t->nama_pelanggan ?: '-' }}</td>
                    <td>{{ $t->formatted_total }}</td>
                    <td>{{ strtoupper($t->metode_pembayaran) }}</td>
                    <td>
                        <span class="badge {{ $t->status === 'selesai' ? 'badge-green' : 'badge-red' }}">
                            {{ $t->status === 'selesai' ? 'Selesai' : 'Batal' }}
                        </span>
                    </td>
                    <td style="white-space:nowrap">
                        <a href="{{ route('admin.pesanan.edit', $t) }}" class="action-link">Koreksi</a>
                        @if($t->status !== 'batal')
                        <form method="POST" action="{{ route('admin.pesanan.batal', $t) }}" style="display:inline" onsubmit="return confirm('Batalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-link del" style="background:none;border:none;cursor:pointer;padding:0;margin-left:.6rem">Batalkan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--muted);padding:2rem">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $pesanan->links() }}</div>
</div>
@endsection
