@extends('admin.layouts.app')

@section('title', 'Profil UMKM')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Profil Usaha</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('admin.profil.update') }}" method="POST" class="row g-3">
        @csrf @method('PUT')
        <div class="col-md-6">
            <label for="nama_usaha" class="form-label">Nama Usaha</label>
            <input type="text" class="form-control" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $profil->nama_usaha) }}" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $profil->email) }}">
        </div>
        <div class="col-md-6">
            <label for="whatsapp" class="form-label">WhatsApp</label>
            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $profil->whatsapp) }}">
        </div>
        <div class="col-md-6">
            <label for="instagram" class="form-label">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram', $profil->instagram) }}">
        </div>
        <div class="col-md-6">
            <label for="facebook" class="form-label">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook', $profil->facebook) }}">
        </div>
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="2">{{ old('alamat', $profil->alamat) }}</textarea>
        </div>
        <div class="col-12">
            <label for="teks_tentang_kami" class="form-label">Tentang Kami</label>
            <textarea class="form-control" id="teks_tentang_kami" name="teks_tentang_kami" rows="4">{{ old('teks_tentang_kami', $profil->teks_tentang_kami) }}</textarea>
        </div>
        <div class="col-md-6">
            <label for="jam_buka" class="form-label">Jam Buka</label>
            <input type="text" class="form-control" id="jam_buka" name="jam_buka" value="{{ old('jam_buka', $profil->jam_buka) }}">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
