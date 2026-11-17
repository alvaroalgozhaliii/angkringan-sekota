@extends('admin.layouts.app')
@section('page-title', 'Pengaturan transaksi')
@section('title', 'Pengaturan transaksi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pengaturan transaksi</div>
        <div class="page-sub">Tentukan metode yang boleh dipakai kasir: tunai dan QRIS.</div>
    </div>
</div>

<div class="panel" style="max-width:560px">
    <form method="POST" action="{{ route('admin.pengaturan.transaksi.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.9rem;cursor:pointer">
                <input type="checkbox" name="tunai_aktif" value="1" {{ $pengaturan->tunai_aktif ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                Tunai (cash) aktif
            </label>
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.9rem;cursor:pointer">
                <input type="checkbox" name="qris_aktif" value="1" {{ $pengaturan->qris_aktif ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                QRIS aktif
            </label>
        </div>
        <p style="font-size:.8rem;color:var(--muted);margin-bottom:1rem">Minimal satu metode harus tetap aktif. Kasir tidak bisa menagih dengan metode yang dimatikan.</p>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan pengaturan</button>
    </form>
</div>
@endsection
