@extends('admin.layouts.app')
@section('page-title', 'Menu & Produk')
@section('title', 'Produk')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Daftar Produk</div>
        <div class="page-sub">Kelola semua menu dan produk yang ditampilkan di web profil.</div>
    </div>
    <a href="{{ route('admin.produk.create') }}" class="btn-sm btn-lg btn-red">+ Tambah Produk</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produk as $p)
                <tr>
                    <td style="color:var(--muted)">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem">
                            @if($p->gambar)
                                <img src="{{ asset('storage/'.$p->gambar) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                            @else
                                <div style="width:40px;height:40px;border-radius:8px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;font-size:1.2rem">🍽️</div>
                            @endif
                            <strong>{{ $p->nama }}</strong>
                        </div>
                    </td>
                    <td><span class="badge badge-gray">{{ $p->kategori }}</span></td>
                    <td><span class="price-pill">Rp {{ number_format($p->harga,0,',','.') }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.produk.toggle', $p) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" style="background:none;border:none;cursor:pointer;padding:0">
                                <span class="badge {{ $p->status === 'tayang' ? 'badge-green' : 'badge-gray' }}">
                                    {{ $p->status === 'tayang' ? 'Tayang' : 'Nonaktif' }}
                                </span>
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex;gap:1rem">
                            <a href="{{ route('admin.produk.edit', $p) }}" class="action-link">Ubah</a>
                            <form method="POST" action="{{ route('admin.produk.destroy', $p) }}"
                                  onsubmit="return confirm('Hapus produk {{ $p->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;cursor:pointer;padding:0" class="action-link del">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:3rem">
                    Belum ada produk. <a href="{{ route('admin.produk.create') }}" style="color:var(--red)">Tambah sekarang</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
