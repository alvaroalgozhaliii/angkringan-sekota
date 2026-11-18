@extends('admin.layouts.app')
@section('page-title', 'Kasir')
@section('title', 'Kasir')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kasir</div>
        <div class="page-sub">Catat pesanan langsung di toko. Pembayaran langsung terverifikasi dan stok otomatis berkurang.</div>
    </div>
    <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-white">Lihat pesanan →</a>
</div>

<form method="POST" action="{{ route('pegawai.kasir.store') }}" id="form-kasir">
    @csrf
    <input type="hidden" name="items" id="items">
    <div class="two-col">
        <div class="panel">
            <div class="panel-title">Data Pesanan</div>
            <div class="form-group">
                <label class="form-label" for="nama_pelanggan">Nama pelanggan (opsional)</label>
                <input id="nama_pelanggan" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" maxlength="100" placeholder="Mis. Pelanggan walk-in">
            </div>
            <div class="form-group">
                <label class="form-label" for="nomor_wa">Nomor WhatsApp (opsional)</label>
                <input id="nomor_wa" name="nomor_wa" class="form-control" value="{{ old('nomor_wa') }}" maxlength="30" placeholder="0812-3456-7890">
            </div>
            <div class="form-group">
                <label class="form-label" for="metode_pembayaran">Metode pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                    <option value="tunai" {{ old('metode_pembayaran') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ old('metode_pembayaran') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
                @error('metode_pembayaran') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="uang_diterima">Uang diterima (Rp)</label>
                <input id="uang_diterima" name="uang_diterima" type="number" min="0" step="1" class="form-control" value="{{ old('uang_diterima') }}" required>
                <div class="hint">Untuk QRIS, nominal otomatis sama dengan total belanja.</div>
                @error('uang_diterima') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="catatan">Catatan (opsional)</label>
                <textarea id="catatan" name="catatan" class="form-control" rows="2" maxlength="500">{{ old('catatan') }}</textarea>
            </div>
            <div class="divider"></div>
            <div style="display:flex;justify-content:space-between;align-items:baseline">
                <strong>Total belanja</strong>
                <strong id="total-belanja" style="font-size:1.2rem">Rp0</strong>
            </div>
            @error('items') <div class="field-error" style="margin-top:.5rem">{{ $message }}</div> @enderror
            <button type="submit" class="btn-sm btn-lg btn-red" style="width:100%;margin-top:1rem">Simpan pesanan kasir</button>
        </div>

        <div class="panel">
            <div class="panel-title">Pilih Menu ({{ $produk->count() }})</div>
            @if($produk->isEmpty())
                <div class="hint">Belum ada produk tayang. Tambahkan produk terlebih dahulu.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Menu</th><th>Harga</th><th>Stok</th><th style="width:90px">Qty</th></tr></thead>
                        <tbody>
                            @foreach($produk as $item)
                                <tr data-produk data-id="{{ $item->id }}" data-harga="{{ (float) $item->harga }}">
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        <div class="hint">{{ $item->kategori }}</div>
                                    </td>
                                    <td>{{ $item->harga_format }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>
                                        <input type="number" class="form-control qty-kasir" min="0" max="{{ $item->stok }}" step="1" value="0" style="width:75px;padding:.35rem" data-maks="{{ $item->stok }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('form-kasir');
    const hidden = document.getElementById('items');
    const metode = document.getElementById('metode_pembayaran');
    const uang = document.getElementById('uang_diterima');
    const totalEl = document.getElementById('total-belanja');
    const rows = Array.from(document.querySelectorAll('[data-produk]'));
    const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n);

    function hitung() {
        let total = 0;
        rows.forEach((row) => {
            const qty = Math.max(0, Number(row.querySelector('.qty-kasir').value) || 0);
            total += Number(row.dataset.harga) * qty;
        });
        totalEl.textContent = rupiah(total);
        return total;
    }

    rows.forEach((row) => {
        row.querySelector('.qty-kasir').addEventListener('input', hitung);
    });
    metode.addEventListener('change', () => {
        if (metode.value === 'qris') {
            uang.value = hitung();
            uang.readOnly = true;
        } else {
            uang.readOnly = false;
        }
    });

    form.addEventListener('submit', (e) => {
        const total = hitung();
        const items = [];
        for (const row of rows) {
            const input = row.querySelector('.qty-kasir');
            const qty = Math.max(0, Number(input.value) || 0);
            if (qty < 1) continue;
            const maks = Number(input.dataset.maks || 0);
            if (qty > maks) {
                e.preventDefault();
                alert('Stok tidak mencukupi untuk salah satu menu.');
                input.focus();
                return;
            }
            items.push({ id: row.dataset.id, qty: qty });
        }
        if (items.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu menu.');
            return;
        }
        hidden.value = JSON.stringify(items);
        if (metode.value === 'qris') {
            uang.value = total;
        }
    });

    hitung();
})();
</script>
@endpush
