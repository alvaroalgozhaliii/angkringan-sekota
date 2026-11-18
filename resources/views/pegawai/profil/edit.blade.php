@extends('admin.layouts.app')
@section('page-title', 'Profil Saya')
@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Profil Saya</div>
        <div class="page-sub">Ubah nama, kontak, dan foto akun Anda. Email tidak dapat diubah di halaman ini.</div>
    </div>
    <a href="{{ route('pegawai.password.edit') }}" class="btn-sm btn-white">Ubah password</a>
</div>

<div class="panel" style="max-width:620px">
    <form method="POST" action="{{ route('pegawai.profil.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label" for="name">Nama lengkap</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}" required maxlength="255">
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="kontak">Kontak</label>
            <input id="kontak" name="kontak" type="text" class="form-control" value="{{ old('kontak', auth()->user()->kontak) }}" placeholder="Contoh: 0812-3456-7890" maxlength="50">
            <div class="hint">Nomor telepon / WhatsApp internal yang bisa dihubungi.</div>
            @error('kontak') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="foto">Foto profil</label>
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.6rem">
                <img src="{{ auth()->user()->fotoUrl() }}" alt="Foto profil" class="thumb">
                <div class="hint">Format JPG, PNG, atau WebP. Maksimal 2 MB.</div>
            </div>
            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
            @error('foto') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan profil</button>
    </form>
</div>
@endsection
