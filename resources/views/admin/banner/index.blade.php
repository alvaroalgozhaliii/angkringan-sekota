@extends('admin.layouts.app')
@section('page-title', 'Banner Promo')
@section('title', 'Banner Promo')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Banner Promo &amp; Pengumuman</div>
        <div class="page-sub">Kelola banner slider yang tampil di halaman beranda pengunjung website.</div>
    </div>
    <a href="{{ route('admin.banner.create') }}" class="btn-sm btn-lg btn-red">+ Tambah Banner</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">Urutan</th>
                    <th style="width: 180px;">Pratinjau Banner</th>
                    <th>Judul &amp; Informasi</th>
                    <th>Tautan (Link)</th>
                    <th>Status</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $b)
                <tr>
                    <td style="text-align:center; font-weight:700; color:var(--red);">
                        #{{ $b->urutan }}
                    </td>
                    <td>
                        <div style="width:160px; height:80px; border-radius:10px; overflow:hidden; border:1px solid var(--border); box-shadow:0 2px 6px rgba(0,0,0,0.06); background:#1a1a1a;">
                            <img src="{{ $b->gambar_url }}" alt="{{ $b->judul ?? 'Banner' }}" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    </td>
                    <td>
                        <div style="font-size:0.95rem; font-weight:700; color:var(--text); margin-bottom:0.25rem;">
                            {{ $b->judul ?: '(Tanpa Judul)' }}
                        </div>
                        @if($b->deskripsi)
                            <div style="font-size:0.8rem; color:var(--muted); line-height:1.4;">
                                {{ $b->deskripsi }}
                            </div>
                        @else
                            <span style="font-size:0.75rem; color:#9CA3AF; font-style:italic;">Hanya gambar banner</span>
                        @endif
                    </td>
                    <td>
                        @if($b->link)
                            <a href="{{ $b->link }}" target="_blank" style="color:var(--red); font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:4px; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                <span>🔗</span> {{ $b->link }}
                            </a>
                        @else
                            <span style="color:var(--muted); font-size:0.8rem;">-</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.banner.toggle', $b) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="Klik untuk ubah status tayang" style="background:none;border:none;cursor:pointer;padding:0">
                                <span class="badge {{ $b->is_aktif ? 'badge-green' : 'badge-gray' }}">
                                    {{ $b->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:1rem; align-items:center;">
                            <a href="{{ route('admin.banner.edit', $b) }}" class="action-link">Ubah</a>
                            <form method="POST" action="{{ route('admin.banner.destroy', $b) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;padding:0" class="action-link del">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:var(--muted); padding:3rem">
                        Belum ada banner promo. <a href="{{ route('admin.banner.create') }}" style="color:var(--red); font-weight:600;">Tambah banner sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
