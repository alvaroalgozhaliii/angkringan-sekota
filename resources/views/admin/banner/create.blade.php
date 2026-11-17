@extends('admin.layouts.app')
@section('page-title', 'Tambah Banner')
@section('title', 'Tambah Banner')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Tambah Banner Baru</div>
        <div class="page-sub">Upload gambar banner promo/pengumuman untuk ditampilkan di beranda.</div>
    </div>
    <a href="{{ route('admin.banner.index') }}" class="btn-sm btn-white">← Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0; padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel" style="max-width:760px">
    <form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Upload Gambar Banner *</label>
            <input type="file" name="gambar" class="form-control" accept="image/*" id="gambarInput" required>
            <div style="font-size:.75rem;color:var(--muted);margin-top:.35rem">
                Rekomendasi rasio lanskap lebar (misal: 1200x420 px, 1600x560 px, atau 16:9). JPG, PNG, WEBP, GIF. Maks 5MB.
            </div>
            <div id="previewWrap" style="margin-top:1rem;display:none">
                <div style="font-size:0.75rem;font-weight:600;color:var(--muted);margin-bottom:0.3rem">Pratinjau:</div>
                <div style="max-width:100%; border-radius:14px; overflow:hidden; border:2px solid var(--border); box-shadow:0 4px 14px rgba(0,0,0,0.08); background:#111;">
                    <img id="previewImg" style="width:100%; max-height:280px; object-fit:cover; display:block;">
                </div>
            </div>
        </div>

        <div class="two-col">
            <div class="form-group">
                <label class="form-label">Judul Banner (Opsional)</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Contoh: Promo Spesial Weekend">
            </div>
            <div class="form-group">
                <label class="form-label">Tautan / Link Tujuan (Opsional)</label>
                <input type="text" name="link" class="form-control" value="{{ old('link') }}" placeholder="Contoh: #menu atau https://wa.me/...">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi / Keterangan Singkat (Opsional)</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Teks keterangan singkat yang tampil di atas banner...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="two-col">
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}" min="0" style="max-width:160px">
                <div style="font-size:.72rem;color:var(--muted);margin-top:.25rem">Angka lebih kecil tampil lebih awal.</div>
            </div>

            <div class="form-group" style="display:flex; flex-direction:column; justify-content:center;">
                <label class="form-label" style="margin-bottom:0.6rem">Status Tayang</label>
                <label style="display:inline-flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.88rem; font-weight:500;">
                    <input type="checkbox" name="is_aktif" value="1" {{ old('is_aktif', '1') == '1' ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--red);">
                    <span>Langsung Aktifkan Banner di Beranda</span>
                </label>
            </div>
        </div>

        <div class="btn-row" style="margin-top:1.5rem; border-top:1px solid var(--border); padding-top:1rem;">
            <button type="submit" class="btn-sm btn-lg btn-red">Simpan Banner</button>
            <a href="{{ route('admin.banner.index') }}" class="btn-sm btn-lg btn-white">Batal</a>
        </div>
    </form>
</div>

<script>
    const input = document.getElementById('gambarInput');
    const wrap = document.getElementById('previewWrap');
    const img = document.getElementById('previewImg');
    if (input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    wrap.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                wrap.style.display = 'none';
            }
        });
    }
</script>
@endsection
