@extends('admin.layouts.app')
@section('page-title', 'Tambah Produk')
@section('title', 'Tambah Produk')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Tambah Produk Baru</div>
        <div class="page-sub">Isi detail produk yang akan ditampilkan di web profil.</div>
    </div>
    <a href="{{ route('admin.produk.index') }}" class="btn-sm btn-white">← Kembali</a>
</div>

<div class="panel" style="max-width:700px">
    <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="two-col">
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required placeholder="Mix Platter">
            </div>
            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="kategori" class="form-control" required>
                    <option value="Makanan" {{ old('kategori') === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Minuman" {{ old('kategori') === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    <option value="Snack"   {{ old('kategori') === 'Snack'   ? 'selected' : '' }}>Snack</option>
                    <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required min="0" step="500" placeholder="12000">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="tayang"       {{ old('status','tayang') === 'tayang'       ? 'selected' : '' }}>Tayang</option>
                    <option value="tidak_tayang" {{ old('status') === 'tidak_tayang' ? 'selected' : '' }}>Tidak Tayang</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat produk...">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Foto Produk</label>
            <input type="file" name="gambar" class="form-control" accept="image/*" id="gambarInput">
            <div id="previewWrap" style="margin-top:.6rem;display:none">
                <img id="previewImg" style="max-width:160px;border-radius:10px;border:1px solid var(--border)">
            </div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:.3rem">JPG, PNG, WEBP. Maks 2MB.</div>
        </div>
        <div class="form-group">
            <label class="form-label">Urutan Tampil</label>
            <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}" min="0" style="width:120px">
        </div>
        <div class="btn-row">
            <button type="submit" class="btn-sm btn-lg btn-red">Simpan Produk</button>
            <a href="{{ route('admin.produk.index') }}" class="btn-sm btn-lg btn-white">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('gambarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewWrap').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
