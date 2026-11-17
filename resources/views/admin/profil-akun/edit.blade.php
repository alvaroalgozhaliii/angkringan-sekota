@extends('admin.layouts.app')
@section('page-title', 'Profil pengguna')
@section('title', 'Profil pengguna')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Profil pengguna</div>
        <div class="page-sub">Nama, email, dan password akun yang sedang login. Hak akses: {{ $user->roleLabel() }}.</div>
    </div>
</div>

<div class="panel" style="max-width:560px">
    <form method="POST" action="{{ route('admin.profil-akun.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label" for="name">Nama</label>
            <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password baru</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti" autocomplete="new-password">
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan profil</button>
    </form>
</div>
@endsection
