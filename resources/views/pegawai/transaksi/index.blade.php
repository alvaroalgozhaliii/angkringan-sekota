@extends('admin.layouts.app')
@section('page-title', 'Metode Transaksi')
@section('title', 'Metode Transaksi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Metode Transaksi</div>
        <div class="page-sub">Atur QRIS, pembayaran tunai, pengiriman/ambil di toko, dan batas waktu bayar.</div>
    </div>
</div>

<form method="POST" action="{{ route('pegawai.transaksi.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="panel">
        <div class="panel-title">1. Metode Pembayaran</div>
        <div class="two-col">
            <div>
                <label style="display:flex;gap:.6rem;align-items:center;font-size:.88rem;cursor:pointer;margin-bottom:1rem">
                    <input type="checkbox" name="tunai_aktif" value="1" {{ old('tunai_aktif', $pengaturan->tunai_aktif) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                    <strong>Tunai (cash) aktif</strong>
                </label>
                <div class="form-group">
                    <label class="form-label" for="tunai_syarat">Syarat pembayaran tunai</label>
                    <textarea id="tunai_syarat" name="tunai_syarat" class="form-control" rows="3" maxlength="1000">{{ old('tunai_syarat', $pengaturan->tunai_syarat) }}</textarea>
                    @error('tunai_syarat') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="tunai_instruksi">Instruksi untuk kasir/pelanggan</label>
                    <textarea id="tunai_instruksi" name="tunai_instruksi" class="form-control" rows="3" maxlength="2000">{{ old('tunai_instruksi', $pengaturan->tunai_instruksi) }}</textarea>
                    @error('tunai_instruksi') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <strong style="font-size:.88rem">QRIS</strong>
                <div class="hint" style="margin-bottom:.75rem">Status: {{ $pengaturan->qris_aktif ? 'Aktif' : 'Nonaktif' }}</div>
                @if($pengaturan->qrisUrl())<img src="{{ $pengaturan->qrisUrl() }}" alt="QRIS" class="thumb-lg" style="max-height:180px;object-fit:contain;margin-bottom:.6rem">@endif
                <div class="hint">Merchant: {{ $pengaturan->qris_nama_merchant ?: '-' }}</div>
                <div class="hint">Kode QRIS, merchant, dan batas bayar diatur Owner di halaman Pengaturan transaksi. Pegawai hanya memeriksa bukti transfer pelanggan.</div>
            </div>
        </div>
        <div class="hint">Minimal satu metode pembayaran harus aktif.</div>
    </div>

    <div class="panel">
        <div class="panel-title">2. Pengiriman &amp; Ambil di Toko</div>
        <div class="two-col">
            <div>
                <label style="display:flex;gap:.6rem;align-items:center;font-size:.88rem;cursor:pointer;margin-bottom:1rem">
                    <input type="checkbox" name="pengiriman_aktif" value="1" {{ old('pengiriman_aktif', $pengaturan->pengiriman_aktif) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                    <strong>Layanan pengiriman aktif</strong>
                </label>
                <div class="form-group">
                    <label class="form-label" for="area_pengiriman">Area pengiriman</label>
                    <textarea id="area_pengiriman" name="area_pengiriman" class="form-control" rows="3">{{ old('area_pengiriman', $pengaturan->area_pengiriman) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="tarif_pengiriman">Tarif pengiriman (Rp)</label>
                    <input id="tarif_pengiriman" name="tarif_pengiriman" type="number" min="0" step="1" class="form-control" value="{{ old('tarif_pengiriman', $pengaturan->tarif_pengiriman) }}">
                    @error('tarif_pengiriman') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div>
                <label style="display:flex;gap:.6rem;align-items:center;font-size:.88rem;cursor:pointer;margin-bottom:1rem">
                    <input type="checkbox" name="ambil_di_toko_aktif" value="1" {{ old('ambil_di_toko_aktif', $pengaturan->ambil_di_toko_aktif) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--red)">
                    <strong>Ambil di toko aktif</strong>
                </label>
                <div class="form-group">
                    <label class="form-label" for="alamat_toko">Alamat toko untuk pengambilan</label>
                    <textarea id="alamat_toko" name="alamat_toko" class="form-control" rows="3" maxlength="500">{{ old('alamat_toko', $pengaturan->alamat_toko) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title">3. Batas Waktu Bayar QRIS</div>
        <div class="hint">Batas pembayaran QRIS saat ini: {{ $pengaturan->batas_bayar_menit }} menit. Diatur oleh Owner di halaman Pengaturan transaksi.</div>
        <button type="submit" class="btn-sm btn-lg btn-red" style="margin-top:1rem">Simpan pengaturan transaksi</button>
    </div>
</form>
@endsection
