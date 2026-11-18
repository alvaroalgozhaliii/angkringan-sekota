@extends('admin.layouts.app')
@section('page-title', 'Ubah Password')
@section('title', 'Ubah Password')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Ubah Password</div>
        <div class="page-sub">Password baru minimal 8 karakter dan memuat huruf kecil, huruf besar, serta angka.</div>
    </div>
    <a href="{{ route('pegawai.profil.edit') }}" class="btn-sm btn-white">← Kembali ke profil</a>
</div>

<div class="panel" style="max-width:560px">
    <form method="POST" action="{{ route('pegawai.password.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label" for="password_lama">Password lama</label>
            <input id="password_lama" name="password_lama" type="password" class="form-control" required autocomplete="current-password">
            @error('password_lama') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password_baru">Password baru</label>
            <input id="password_baru" name="password_baru" type="password" class="form-control" required autocomplete="new-password">
            @error('password_baru') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password_baru_confirmation">Konfirmasi password baru</label>
            <input id="password_baru_confirmation" name="password_baru_confirmation" type="password" class="form-control" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan password</button>
    </form>
</div>
@endsection
