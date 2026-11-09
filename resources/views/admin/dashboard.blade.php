@extends('admin.layouts.app')
@section('page-title', 'DASHBOARD ADMIN')
@section('title', 'Dashboard')

@section('content')
<p style="color:var(--muted);font-size:.88rem;margin-bottom:1.5rem;margin-top:-.5rem">
    Kelola isi web Angkringan Sekota Tulungagung.
</p>

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Produk tayang</div>
        <div class="stat-value red">{{ $totalProdukTayang }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pesan belum dibaca</div>
        <div class="stat-value">{{ $pesanBelumDibaca > 0 ? $pesanBelumDibaca : '[JUMLAH]' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Produk terlaris</div>
        <div class="stat-value" style="font-size:1.1rem">{{ $produkTerlaris?->nama ?? '[NAMA]' }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Kunjungan bulan ini</div>
        <div class="stat-value">[JUMLAH]</div>
    </div>
</div>

{{-- TWO COL: Profil form + DB info --}}
<div class="two-col">

    {{-- PROFIL & KONTAK --}}
    <div class="panel">
        <div class="panel-title">Profil &amp; Kontak</div>
        <form method="POST" action="{{ route('admin.profil.update') }}">
            @csrf
            <div class="two-col" style="gap:.85rem">
                <div class="form-group">
                    <label class="form-label">Nama usaha</label>
                    <input type="text" name="nama_usaha" class="form-control" value="{{ $profil->nama_usaha }}">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ $profil->whatsapp }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{ $profil->instagram }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $profil->alamat }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Teks Tentang Kami</label>
                <textarea name="teks_tentang_kami" class="form-control" rows="4">{{ $profil->teks_tentang_kami }}</textarea>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-sm btn-lg btn-red">Simpan Perubahan</button>
                <a href="{{ route('home') }}" target="_blank" class="btn-sm btn-lg btn-white">Pratinjau</a>
            </div>
        </form>
    </div>

    {{-- DATABASE INFO --}}
    <div class="panel">
        <div class="panel-title">Database Bersama</div>
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem;line-height:1.6">
            Admin dan web profil membaca data yang sama.
        </p>
        <table class="db-table">
            <thead>
                <tr>
                    <td style="font-weight:700;color:var(--text);font-size:.8rem">Tabel</td>
                    <td style="font-weight:700;color:var(--text);font-size:.8rem">Dipakai oleh</td>
                </tr>
            </thead>
            <tbody>
                <tr><td>profil_usaha</td><td>Admin, Web Profil</td></tr>
                <tr><td>produk</td><td>Admin, Web Profil</td></tr>
                <tr><td>pesan</td><td>Admin, Web Profil</td></tr>
                <tr><td>users</td><td>Admin saja</td></tr>
            </tbody>
        </table>
        <div style="margin-top:1rem;padding:.75rem;background:#F0FDF4;border-radius:8px;border:1px solid #A7F3D0">
            <div style="font-size:.78rem;font-weight:600;color:#065F46;margin-bottom:.25rem">Database: sekota</div>
            <div style="font-size:.75rem;color:#065F46">Status: <strong>Tersambung ✅</strong></div>
        </div>
    </div>
</div>

{{-- PRODUK TERLARIS TABLE --}}
<div class="panel">
    <div class="panel-title-row">
        <div class="panel-title">Produk Terlaris</div>
        <a href="{{ route('admin.produk.create') }}" class="btn-sm btn-red">+ Tambah Produk</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama produk</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($semuaProduk as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td><span class="price-pill">Rp {{ number_format($p->harga,0,',','.') }}</span></td>
                    <td>
                        <span class="badge {{ $p->status === 'tayang' ? 'badge-green' : 'badge-gray' }}">
                            {{ $p->status === 'tayang' ? 'Tayang' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.produk.edit', $p) }}" class="action-link">Ubah</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:2rem">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
