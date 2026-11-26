@extends('admin.layouts.app')
@section('page-title', 'Produk & Stok')
@section('title', 'Produk & Stok')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Produk &amp; Stok</div>
        <div class="page-sub">Kelola menu, gambar, stok, status tayang, dan tanda best seller.</div>
    </div>
</div>

<div class="panel">
    <form method="GET" action="{{ route('pegawai.produk.index') }}" class="filter-grid">
        <div class="form-group">
            <label class="form-label">Cari</label>
            <input name="q" class="form-control" value="{{ request('q') }}" placeholder="Nama atau kategori">
        </div>
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control">
                <option value="">Semua kategori</option>
                @foreach(['Makanan', 'Minuman', 'Camilan'] as $kat)
                    <option value="{{ $kat }}" {{ strcasecmp((string)request('kategori'), $kat) === 0 ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">Semua status</option>
                @foreach(['tayang', 'aktif', 'nonaktif'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label style="display:flex;gap:.5rem;align-items:center;font-size:.83rem;cursor:pointer;padding-top:1.5rem">
                <input type="checkbox" name="stok_menipis" value="1" {{ request('stok_menipis') ? 'checked' : '' }}> Stok ≤ 5
            </label>
        </div>
        <div style="grid-column:1/-1;display:flex;gap:.5rem">
            <button class="btn-sm btn-red">Filter</button>
            <a href="{{ route('pegawai.produk.index') }}" class="btn-sm btn-white">Reset</a>
        </div>
    </form>
</div>

@if($edit)
    <div class="panel" style="max-width:720px">
        <div class="panel-title-row">
            <div class="panel-title" style="margin-bottom:0;border:none;padding-bottom:0">Ubah produk: {{ $edit->nama }}</div>
            <a href="{{ route('pegawai.produk.index') }}" class="action-link">Batal</a>
        </div>
        <form method="POST" action="{{ route('pegawai.produk.update', $edit) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('pegawai.produk._form', ['produk' => $edit])
            <button type="submit" class="btn-sm btn-lg btn-red">Simpan perubahan</button>
        </form>
    </div>
@else
    <div class="panel" style="max-width:720px">
        <div class="panel-title">Tambah Produk Baru</div>
        <form method="POST" action="{{ route('pegawai.produk.store') }}" enctype="multipart/form-data">
            @csrf
            @include('pegawai.produk._form', ['produk' => null])
            <button type="submit" class="btn-sm btn-lg btn-red">Simpan produk</button>
        </form>
    </div>
@endif

<div class="panel">
    <div class="panel-title">Daftar Produk ({{ $produk->total() }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($produk as $item)
                    <tr>
                        <td><img src="{{ $item->gambar_url }}" alt="{{ $item->nama }}" class="thumb"></td>
                        <td>
                            <strong>{{ $item->nama }}</strong>
                            @if($item->is_best_seller)<div class="hint">⭐ Best seller</div>@endif
                        </td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->harga_format }}</td>
                        <td>
                            <form method="POST" action="{{ route('pegawai.produk.stok', $item) }}" style="display:flex;gap:.35rem;align-items:center">
                                @csrf @method('PATCH')
                                <input type="number" name="stok" min="0" value="{{ $item->stok }}" class="form-control" style="width:85px;padding:.35rem">
                                <button class="btn-sm">Set</button>
                            </form>
                        </td>
                        <td><span class="badge {{ $item->status === 'tayang' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a class="action-link" href="{{ route('pegawai.produk.index', ['edit' => $item->id] + request()->query()) }}">Ubah</a>
                            <form method="POST" action="{{ route('pegawai.produk.bestSeller', $item) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="action-link" style="background:none;border:none;cursor:pointer">{{ $item->is_best_seller ? 'Hapus ⭐' : 'Jadikan ⭐' }}</button>
                            </form>
                            <form method="POST" action="{{ route('pegawai.produk.destroy', $item) }}" onsubmit="return confirm('Hapus produk {{ addslashes($item->nama) }}?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="action-link del" style="background:none;border:none;cursor:pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--muted)">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $produk->links() }}</div>
</div>
@endsection
