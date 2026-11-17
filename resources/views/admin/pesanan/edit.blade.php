@extends('admin.layouts.app')
@section('page-title', 'Koreksi pesanan')
@section('title', 'Koreksi pesanan')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Koreksi {{ $transaksi->nomor_transaksi }}</div>
        <div class="page-sub">Kasir: {{ $transaksi->nama_kasir }}. Waktu: {{ $transaksi->formatted_tanggal_waktu }}.</div>
    </div>
    <a href="{{ route('admin.pesanan.index') }}" class="btn-sm btn-white">Kembali</a>
</div>

<div class="panel" style="max-width:720px">
    <form method="POST" action="{{ route('admin.pesanan.update', $transaksi) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label" for="nama_pelanggan">Nama pelanggan</label>
            <input id="nama_pelanggan" type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="catatan">Catatan</label>
            <textarea id="catatan" name="catatan" class="form-control" rows="3">{{ old('catatan', $transaksi->catatan) }}</textarea>
        </div>
        <div class="two-col">
            <div class="form-group">
                <label class="form-label" for="metode_pembayaran">Metode bayar</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control">
                    @foreach($metode as $m)
                        <option value="{{ $m }}" {{ old('metode_pembayaran', $transaksi->metode_pembayaran) === $m ? 'selected' : '' }}>
                            {{ $m === 'tunai' ? 'Tunai (cash)' : 'QRIS' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="selesai" {{ old('status', $transaksi->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ old('status', $transaksi->status) === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="uang_diterima">Uang diterima (wajib jika tunai dan status selesai)</label>
            <input id="uang_diterima" type="number" min="0" name="uang_diterima" class="form-control" value="{{ old('uang_diterima', (int) $transaksi->uang_diterima) }}">
        </div>

        <div class="panel-title">Item</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->details as $d)
                    <tr>
                        <td>{{ $d->nama_produk }}</td>
                        <td>{{ $d->harga_format }}</td>
                        <td style="width:120px">
                            <input type="number" min="1" max="999" name="jumlah[{{ $d->id }}]" class="form-control" value="{{ old('jumlah.'.$d->id, $d->jumlah) }}" required>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="btn-row">
            <button type="submit" class="btn-sm btn-lg btn-red">Simpan koreksi</button>
        </div>
    </form>
</div>
@endsection
