@extends('admin.layouts.app')
@section('page-title', 'Kasir')
@section('title', 'Kasir')

@push('styles')
<style>
    .qty-stepper {
        display: inline-flex;
        align-items: center;
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,.03);
        transition: border-color .2s, box-shadow .2s;
        vertical-align: middle;
    }
    .qty-stepper:focus-within {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(200,16,46,.1);
    }
    .btn-qty {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F9FAFB;
        color: var(--text);
        border: none;
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        user-select: none;
        touch-action: manipulation;
        transition: background-color .15s, color .15s;
    }
    .btn-qty:hover:not(:disabled) {
        background: #FEE2E2;
        color: var(--red);
    }
    .btn-qty:active:not(:disabled) {
        background: #FECACA;
        color: var(--red-dark);
    }
    .btn-qty:focus-visible {
        outline: 2px solid var(--red);
        outline-offset: -2px;
    }
    .btn-qty:disabled {
        opacity: 0.35;
        cursor: not-allowed;
        background: #F3F4F6;
    }
    .qty-stepper .qty-kasir {
        width: 48px;
        height: 36px;
        padding: 0 .25rem;
        text-align: center;
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        border-radius: 0;
        font-weight: 600;
        font-size: .92rem;
        color: var(--text);
        background: transparent;
        box-shadow: none;
        -moz-appearance: textfield;
    }
    .qty-stepper .qty-kasir::-webkit-inner-spin-button,
    .qty-stepper .qty-kasir::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .qty-stepper .qty-kasir:focus {
        outline: none;
        background: #FEF2F2;
        box-shadow: none;
    }
    @media (max-width: 768px) {
        .btn-qty {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1.25rem;
        }
        .qty-stepper .qty-kasir {
            width: 44px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>
@endpush

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
                        <thead><tr><th>Menu</th><th>Harga</th><th>Stok</th><th style="width:130px;text-align:center">Qty</th></tr></thead>
                        <tbody>
                            @foreach($produk as $item)
                                <tr data-produk data-id="{{ $item->id }}" data-harga="{{ (float) $item->harga }}">
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        <div class="hint">{{ $item->kategori }}</div>
                                    </td>
                                    <td>{{ $item->harga_format }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td style="text-align:center">
                                        <div class="qty-stepper">
                                            <button type="button" class="btn-qty btn-qty-minus" aria-label="Kurangi jumlah {{ $item->nama }}" data-action="minus" {{ $item->stok <= 0 ? 'disabled' : '' }}>−</button>
                                            <input type="number" class="form-control qty-kasir" min="0" max="{{ $item->stok }}" step="1" value="0" data-maks="{{ $item->stok }}" inputmode="numeric" aria-label="Jumlah {{ $item->nama }}" {{ $item->stok <= 0 ? 'disabled' : '' }}>
                                            <button type="button" class="btn-qty btn-qty-plus" aria-label="Tambah jumlah {{ $item->nama }}" data-action="plus" {{ $item->stok <= 0 ? 'disabled' : '' }}>+</button>
                                        </div>
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

    function updateStepperState(row) {
        const input = row.querySelector('.qty-kasir');
        const btnMinus = row.querySelector('.btn-qty-minus');
        const btnPlus = row.querySelector('.btn-qty-plus');
        const maks = Number(input.dataset.maks || 0);
        const val = Number(input.value) || 0;

        if (maks <= 0) {
            if (btnMinus) btnMinus.disabled = true;
            if (btnPlus) btnPlus.disabled = true;
            input.disabled = true;
            return;
        }

        if (btnMinus) btnMinus.disabled = val <= 0;
        if (btnPlus) btnPlus.disabled = val >= maks;
    }

    function hitung() {
        let total = 0;
        rows.forEach((row) => {
            const input = row.querySelector('.qty-kasir');
            const qty = Math.max(0, Number(input.value) || 0);
            total += Number(row.dataset.harga) * qty;
            updateStepperState(row);
        });
        totalEl.textContent = rupiah(total);
        if (metode && metode.value === 'qris' && uang) {
            uang.value = total;
        }
        return total;
    }

    rows.forEach((row) => {
        const input = row.querySelector('.qty-kasir');
        const btnMinus = row.querySelector('.btn-qty-minus');
        const btnPlus = row.querySelector('.btn-qty-plus');
        const maks = Number(input.dataset.maks || 0);

        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                let current = Number(input.value) || 0;
                if (current > 0) {
                    input.value = current - 1;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                let current = Number(input.value) || 0;
                if (current < maks) {
                    input.value = current + 1;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }

        input.addEventListener('input', () => {
            let val = Number(input.value) || 0;
            if (val < 0) input.value = 0;
            if (val > maks) input.value = maks;
            hitung();
        });

        input.addEventListener('change', () => {
            let val = Number(input.value) || 0;
            if (val < 0) input.value = 0;
            if (val > maks) input.value = maks;
            hitung();
        });
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
