@extends('admin.layouts.app')
@section('page-title', 'Pengaturan transaksi')
@section('title', 'Pengaturan transaksi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pengaturan transaksi</div>
        <div class="page-sub">Atur metode pembayaran di halaman menu. Kode QRIS diinput di sini dan bukti transfer diperiksa manual oleh Pegawai.</div>
    </div>
</div>

<div class="panel" style="max-width:720px">
    <form method="POST" action="{{ route('admin.pengaturan.transaksi.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.9rem;cursor:pointer">
                <input type="checkbox" name="tunai_aktif" value="1" {{ old('tunai_aktif', $pengaturan->tunai_aktif) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                Cash / Tunai aktif
            </label>
            <div class="hint">Pesanan tunai dari website langsung masuk. Pembayaran dilakukan di kasir saat pengambilan atau pengiriman.</div>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.6rem;font-size:.9rem;cursor:pointer">
                <input type="checkbox" name="qris_aktif" value="1" {{ old('qris_aktif', $pengaturan->qris_aktif) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                QRIS aktif
            </label>
            <div class="hint">Pelanggan mengunggah bukti bayar, lalu Pegawai mengecek bukti transaksi sebelum menyetujui.</div>
        </div>

        <div class="divider"></div>

        <div class="form-group">
            <label class="form-label" for="qris_nama_merchant">Nama merchant QRIS</label>
            <input id="qris_nama_merchant" name="qris_nama_merchant" class="form-control" value="{{ old('qris_nama_merchant', $pengaturan->qris_nama_merchant) }}" maxlength="100" placeholder="ANGKRINGAN SEKOTA">
            @error('qris_nama_merchant') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="qris_gambar">Gambar kode QRIS</label>
            @if($pengaturan->qrisUrl())<img src="{{ $pengaturan->qrisUrl() }}" alt="QRIS" class="thumb-lg" style="max-height:220px;object-fit:contain;margin-bottom:.6rem">@endif
            <input id="qris_gambar" name="qris_gambar" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
            <div class="hint">JPG, PNG, atau WebP, maksimal 2 MB. Kosongkan jika tidak diganti.</div>
            @error('qris_gambar') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="batas_bayar_menit">Batas waktu bayar QRIS (menit)</label>
            <input id="batas_bayar_menit" name="batas_bayar_menit" type="number" min="1" max="10080" class="form-control" value="{{ old('batas_bayar_menit', $pengaturan->batas_bayar_menit) }}" required>
            @error('batas_bayar_menit') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <p style="font-size:.8rem;color:var(--muted)">Minimal satu metode harus tetap aktif.</p>
        <button type="submit" class="btn-sm btn-lg btn-red">Simpan pengaturan</button>
    </form>
</div>
@endsection
