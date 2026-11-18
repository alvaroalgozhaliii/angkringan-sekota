@extends('admin.layouts.app')
@section('page-title', 'Tampilan Website')
@section('title', 'Tampilan Website')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Tampilan Website</div>
        <div class="page-sub">Perubahan konten langsung tampil di website publik. Nama usaha, logo, WhatsApp, dan email resmi hanya bisa diubah Admin.</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-white">Lihat website ↗</a>
</div>

<div class="panel">
    <div class="panel-title">1. Konten Halaman</div>
    <form method="POST" action="{{ route('pegawai.konten.halaman') }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label" for="judul_beranda">Judul beranda</label>
            <input id="judul_beranda" name="judul_beranda" class="form-control" value="{{ old('judul_beranda', $profil->judul_beranda) }}" maxlength="255">
            @error('judul_beranda') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="deskripsi_beranda">Deskripsi singkat beranda</label>
            <textarea id="deskripsi_beranda" name="deskripsi_beranda" class="form-control" rows="2">{{ old('deskripsi_beranda', $profil->deskripsi_beranda) }}</textarea>
            @error('deskripsi_beranda') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="teks_tentang_kami">Profil usaha (tentang kami)</label>
            <textarea id="teks_tentang_kami" name="teks_tentang_kami" class="form-control" rows="4">{{ old('teks_tentang_kami', $profil->teks_tentang_kami) }}</textarea>
            @error('teks_tentang_kami') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="two-col">
            <div class="form-group">
                <label class="form-label" for="visi">Visi</label>
                <textarea id="visi" name="visi" class="form-control" rows="3">{{ old('visi', $profil->visi) }}</textarea>
                @error('visi') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="misi">Misi</label>
                <textarea id="misi" name="misi" class="form-control" rows="3">{{ old('misi', $profil->misi) }}</textarea>
                @error('misi') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan konten halaman</button>
    </form>
</div>

<div class="panel">
    <div class="panel-title">2. Info Operasional, Lokasi &amp; Sosial Media</div>
    <form method="POST" action="{{ route('pegawai.konten.info') }}">
        @csrf @method('PUT')
        <div class="two-col">
            <div class="form-group">
                <label class="form-label" for="jam_buka">Jam operasional</label>
                <input id="jam_buka" name="jam_buka" class="form-control" value="{{ old('jam_buka', $profil->jam_buka) }}" placeholder="17.00 - 23.00 WIB">
                @error('jam_buka') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat">Alamat / lokasi</label>
                <input id="alamat" name="alamat" class="form-control" value="{{ old('alamat', $profil->alamat) }}">
                @error('alamat') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="maps_link">Tautan Google Maps</label>
                <input id="maps_link" name="maps_link" type="url" class="form-control" value="{{ old('maps_link', $profil->maps_link) }}" placeholder="https://maps.app.goo.gl/...">
                @error('maps_link') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="instagram">Instagram</label>
                <input id="instagram" name="instagram" class="form-control" value="{{ old('instagram', $profil->instagram) }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="facebook">Facebook</label>
                <input id="facebook" name="facebook" class="form-control" value="{{ old('facebook', $profil->facebook) }}">
            </div>
            <div class="form-group">
                <label class="form-label" for="tiktok">TikTok</label>
                <input id="tiktok" name="tiktok" class="form-control" value="{{ old('tiktok', $profil->tiktok) }}">
            </div>
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan info</button>
    </form>
</div>

<div class="panel">
    <div class="panel-title">3. Tema &amp; Banner</div>
    <form method="POST" action="{{ route('pegawai.konten.tampilan') }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label" for="warna_tema">Warna tema utama</label>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                <input id="warna_tema" name="warna_tema" type="color" class="form-control" style="width:90px;padding:.25rem" value="{{ old('warna_tema', $profil->warnaTema()) }}">
                <span class="hint">Pilih warna untuk aksen tombol, judul, dan elemen utama website.</span>
            </div>
            @error('warna_tema') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;cursor:pointer">
                <input type="checkbox" name="tampilkan_banner" value="1" {{ old('tampilkan_banner', $profil->tampilkan_banner) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                Tampilkan banner di beranda
            </label>
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan tampilan</button>
    </form>

    <div class="divider"></div>
    <strong style="font-size:.9rem">Daftar Banner</strong>
    <div class="table-wrap" style="margin-top:.75rem">
        <table>
            <thead><tr><th>Gambar</th><th>Judul</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($profilBanners as $banner)
                    <tr>
                        <td><img src="{{ $banner->gambar_url }}" alt="Banner" class="thumb"></td>
                        <td>{{ $banner->judul ?: '-' }}</td>
                        <td>{{ $banner->urutan }}</td>
                        <td><span class="badge {{ $banner->is_aktif ? 'badge-green' : 'badge-gray' }}">{{ $banner->is_aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <details>
                                <summary class="action-link">Ubah</summary>
                                <form method="POST" action="{{ route('pegawai.banner.update', $banner) }}" enctype="multipart/form-data" style="margin-top:.6rem;max-width:360px">
                                    @csrf @method('PUT')
                                    <div class="form-group"><label class="form-label">Judul</label><input name="judul" class="form-control" value="{{ $banner->judul }}"></div>
                                    <div class="form-group"><label class="form-label">Ganti gambar (opsional)</label><input name="gambar" type="file" accept="image/*" class="form-control"></div>
                                    <div class="form-group"><label class="form-label">Urutan</label><input name="urutan" type="number" min="0" class="form-control" value="{{ $banner->urutan }}"></div>
                                    <label style="font-size:.82rem"><input type="checkbox" name="is_aktif" value="1" {{ $banner->is_aktif ? 'checked' : '' }}> Aktif</label>
                                    <div style="margin-top:.6rem"><button class="btn-sm btn-red">Simpan</button></div>
                                </form>
                            </details>
                            <form method="POST" action="{{ route('pegawai.banner.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="action-link del" style="background:none;border:none;cursor:pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="hint">Belum ada banner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="divider"></div>
    <strong style="font-size:.9rem">Tambah Banner</strong>
    <form method="POST" action="{{ route('pegawai.banner.store') }}" enctype="multipart/form-data" style="margin-top:.75rem;max-width:560px">
        @csrf
        <div class="form-group"><label class="form-label">Judul</label><input name="judul" class="form-control" maxlength="255"></div>
        <div class="form-group"><label class="form-label">Deskripsi</label><input name="deskripsi" class="form-control" maxlength="255"></div>
        <div class="form-group"><label class="form-label">Tautan (opsional)</label><input name="link" type="url" class="form-control"></div>
        <div class="form-group"><label class="form-label">Gambar banner</label><input name="gambar" type="file" accept="image/jpeg,image/png,image/webp" class="form-control" required><div class="hint">JPG/PNG/WebP, maksimal 2 MB.</div></div>
        <div class="form-group"><label class="form-label">Urutan</label><input name="urutan" type="number" min="0" value="0" class="form-control"></div>
        <label style="font-size:.82rem"><input type="checkbox" name="is_aktif" value="1" checked> Aktif</label>
        <div style="margin-top:.75rem"><button class="btn-sm btn-lg btn-red">Tambah banner</button></div>
    </form>
</div>
@endsection
