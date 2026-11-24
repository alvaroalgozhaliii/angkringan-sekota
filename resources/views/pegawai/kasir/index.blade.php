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
@php
    $itemsLama = [];
    if (old('items')) {
        $decoded = json_decode((string) old('items'), true);
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                $id = (int) ($item['id'] ?? 0);
                $qty = (int) ($item['qty'] ?? 0);
                if ($id > 0 && $qty > 0) {
                    $itemsLama[$id] = $qty;
                }
            }
        }
    }
@endphp

<div class="page-header">
    <div>
        <div class="page-title">Kasir</div>
        <div class="page-sub">Ketuk menu yang dipesan pengunjung, lalu periksa pembayaran sebelum menyimpan.</div>
    </div>
    <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-white">Lihat pesanan</a>
</div>

<form method="POST" action="{{ route('pegawai.kasir.store') }}" id="form-kasir">
    @csrf
    <input type="hidden" name="items" id="items" value="{{ old('items') }}">

    <div class="panel kasir-panel">
        <div class="kasir-toolbar">
            <div class="kasir-search">
                <label class="form-label" for="cari-menu">Cari menu</label>
                <input id="cari-menu" type="search" class="form-control" placeholder="Nama menu" autocomplete="off">
            </div>
            <div class="kasir-toolbar-meta">
                <span id="jumlah-menu" aria-live="polite" class="kasir-count">Menampilkan {{ $produk->count() }} menu</span>
                <button type="button" id="kosongkan-cari" class="btn-sm btn-white" hidden>Kosongkan</button>
            </div>
        </div>

<<<<<<< HEAD
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
=======
        @if($produk->isEmpty())
            <div class="kasir-empty">
                <strong>Belum ada menu tayang.</strong>
                <span>Tambahkan produk di halaman Produk agar bisa dipilih di kasir.</span>
            </div>
        @else
            <div class="kasir-grid" id="kasir-grid">
                @foreach($produk as $item)
                    @php
                        $habis = (int) $item->stok < 1;
                        $qtyAwal = $itemsLama[$item->id] ?? 0;
                        if ($qtyAwal > (int) $item->stok) {
                            $qtyAwal = (int) $item->stok;
                        }
                    @endphp
                    <button
                        type="button"
                        class="menu-tile {{ $habis ? 'is-out' : '' }} {{ $item->is_best_seller && ! $habis ? 'is-best' : '' }}"
                        data-menu
                        data-id="{{ $item->id }}"
                        data-nama="{{ strtolower($item->nama) }}"
                        data-harga="{{ (float) $item->harga }}"
                        data-stok="{{ (int) $item->stok }}"
                        data-qty-awal="{{ $qtyAwal }}"
                        {{ $habis ? 'disabled' : '' }}
                        aria-label="{{ $habis ? $item->nama.', stok habis' : 'Tambah '.$item->nama }}"
                    >
                        <span class="menu-tile-top">
                            <span class="menu-tile-nama">{{ $item->nama }}</span>
                            @if($item->is_best_seller)
                                <span class="menu-tile-best">Terlaris</span>
                            @endif
                        </span>
                        <span class="menu-tile-harga">{{ $item->harga_format }}</span>
                        <span class="menu-tile-bawah">
                            <span class="menu-tile-stok">{{ $habis ? 'Stok habis' : 'Stok '.$item->stok }}</span>
                            <span class="menu-tile-tambah" aria-hidden="true">{{ $habis ? '×' : '+' }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
            <div class="kasir-no-result" id="kasir-no-result" hidden>
                <strong>Tidak ada menu yang cocok.</strong>
                <span>Hapus kata kunci pencarian untuk melihat semua menu.</span>
            </div>
        @endif
>>>>>>> 33dda617376f4c861e8d254db5bceaab96f61929
    </div>

    <div class="kasir-tray" id="kasir-tray">
        <div class="kasir-tray-ringkas">
            <span id="tray-item" aria-live="polite">0 item</span>
            <strong id="tray-total" aria-live="polite">Rp0</strong>
        </div>
        <button type="button" id="buka-checkout" class="btn-sm btn-red" disabled aria-controls="checkout-modal" aria-expanded="false">
            Periksa pesanan
        </button>
    </div>

    <dialog id="checkout-modal" class="checkout-modal" aria-labelledby="checkout-judul">
        <div class="checkout-head">
            <div>
                <div class="panel-title" id="checkout-judul">Periksa pesanan</div>
                <div class="hint">Ubah jumlah menu di sini sebelum menyimpan.</div>
            </div>
            <button type="button" id="tutup-checkout" class="btn-sm btn-white" aria-label="Tutup periksa pesanan">Tutup</button>
        </div>

        <div class="checkout-body">
            <div id="checkout-items" class="checkout-items"></div>
            <p class="checkout-kosong" id="checkout-kosong" hidden>Belum ada menu dipilih. Ketuk menu di halaman kasir untuk menambah.</p>

            <div class="checkout-grid">
                <div class="form-group">
                    <label class="form-label" for="nama_pelanggan">Nama pelanggan (opsional)</label>
                    <input id="nama_pelanggan" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" maxlength="100" placeholder="Pelanggan walk-in">
                </div>
                <div class="form-group">
                    <label class="form-label" for="nomor_wa">Nomor WhatsApp (opsional)</label>
                    <input id="nomor_wa" name="nomor_wa" class="form-control" value="{{ old('nomor_wa') }}" maxlength="30" placeholder="0812-3456-7890">
                </div>
                <div class="form-group">
                    <label class="form-label" for="metode_pembayaran">Metode pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                        <option value="tunai" {{ old('metode_pembayaran', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai</option>
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
                <div class="form-group checkout-catatan">
                    <label class="form-label" for="catatan">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="2" maxlength="500">{{ old('catatan') }}</textarea>
                </div>
            </div>
            @error('items') <div class="field-error checkout-error">{{ $message }}</div> @enderror
            <div class="checkout-error" id="checkout-error" role="alert" hidden></div>
        </div>

        <div class="checkout-foot">
            <div class="checkout-total">
                <span>Total belanja</span>
                <strong id="total-belanja" aria-live="polite">Rp0</strong>
                <span id="kembalian" class="checkout-kembalian"></span>
            </div>
            <button type="submit" class="btn-sm btn-lg btn-red" id="simpan-kasir">Simpan pesanan kasir</button>
        </div>
    </dialog>
</form>
@endsection

@push('styles')
<style>
    .kasir-panel{padding:1rem}
    .page-header .btn-sm,.kasir-toolbar .btn-sm,.checkout-head .btn-sm{min-height:44px}
    .kasir-toolbar{display:flex;align-items:end;justify-content:space-between;gap:1rem;margin-bottom:1rem}
    .kasir-search{flex:1 1 320px;max-width:420px}
    .kasir-toolbar-meta{display:flex;align-items:center;gap:.75rem;padding-bottom:.15rem}
    .kasir-count{font-size:.78rem;color:var(--muted)}
    .kasir-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:.75rem}
    .menu-tile{min-height:132px;display:flex;flex-direction:column;align-items:stretch;justify-content:space-between;gap:.45rem;text-align:left;background:var(--white);border:1px solid var(--border);border-radius:10px;padding:.85rem;color:var(--text);cursor:pointer;transition:border-color .15s,background .15s,transform .15s}
    .menu-tile:hover:not(:disabled){border-color:var(--red);background:#FEF2F2;transform:translateY(-1px)}
    .menu-tile:active:not(:disabled){transform:translateY(0);background:#FEE2E2}
    .menu-tile:focus-visible{outline:2px solid var(--red);outline-offset:2px}
    .menu-tile.is-best{border-top:3px solid var(--red)}
    .menu-tile.is-out{background:#374151;border-color:#374151;color:#F9FAFB;cursor:not-allowed}
    .menu-tile-top{display:flex;align-items:flex-start;justify-content:space-between;gap:.4rem}
    .menu-tile-nama{font-size:.88rem;font-weight:700;line-height:1.35;overflow-wrap:anywhere}
    .menu-tile-best{flex:none;font-size:.65rem;font-weight:700;color:var(--red);background:#FEE2E2;border-radius:4px;padding:.15rem .3rem}
    .menu-tile.is-out .menu-tile-best{color:#F9FAFB;background:#4B5563}
    .menu-tile-harga{font-size:.82rem;font-weight:600;color:var(--red)}
    .menu-tile.is-out .menu-tile-harga{color:#FCA5A5}
    .menu-tile-bawah{display:flex;align-items:center;justify-content:space-between;gap:.5rem}
    .menu-tile-stok{font-size:.72rem;color:var(--muted)}
    .menu-tile.is-out .menu-tile-stok{color:#E5E7EB}
    .menu-tile-tambah{display:grid;place-items:center;width:30px;height:30px;flex:none;border-radius:8px;background:var(--red);color:var(--white);font-size:1.1rem;font-weight:700;line-height:1}
    .menu-tile.is-out .menu-tile-tambah{background:#4B5563;color:#D1D5DB}
    .kasir-empty,.kasir-no-result{display:flex;flex-direction:column;gap:.3rem;padding:2rem 1rem;text-align:center;color:var(--muted);font-size:.85rem}
    .kasir-empty strong,.kasir-no-result strong{color:var(--text)}
    .kasir-tray{position:fixed;left:0;right:0;bottom:0;z-index:80;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.7rem max(1rem,calc((100vw - 960px)/2));background:var(--white);border-top:1px solid var(--border);box-shadow:0 -2px 10px rgba(31,41,55,.06)}
    .kasir-tray-ringkas{display:flex;align-items:baseline;gap:.75rem;min-width:0}
    .kasir-tray-ringkas span{font-size:.8rem;color:var(--muted)}
    .kasir-tray-ringkas strong{font-size:1.05rem;white-space:nowrap}
    .kasir-tray .btn-sm{min-height:44px}
    .content:has(.kasir-tray){padding-bottom:6rem}
    .checkout-modal{width:min(680px,calc(100% - 2rem));max-height:min(92dvh,900px);padding:0;border:1px solid var(--border);border-radius:12px;background:var(--white);color:var(--text);box-shadow:0 20px 50px rgba(31,41,55,.18)}
    .checkout-modal::backdrop{background:rgba(31,41,55,.45)}
    .checkout-head,.checkout-foot{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.15rem}
    .checkout-head{border-bottom:1px solid var(--border)}
    .checkout-foot{border-top:1px solid var(--border);background:var(--gray-100)}
    .checkout-body{padding:1rem 1.15rem;overflow-y:auto;max-height:calc(92dvh - 150px)}
    .checkout-head .panel-title{margin-bottom:.15rem}
    .checkout-items{display:flex;flex-direction:column;gap:.5rem;margin-bottom:1rem}
    .checkout-item{display:grid;grid-template-columns:minmax(0,1fr) auto auto;align-items:center;gap:.75rem;padding:.65rem .75rem;border:1px solid var(--border);border-radius:8px}
    .checkout-item-nama{font-size:.85rem;font-weight:600;overflow-wrap:anywhere}
    .checkout-item-harga{font-size:.75rem;color:var(--muted)}
    .qty-control{display:inline-flex;align-items:center;gap:.3rem}
    .qty-control button{width:34px;height:34px;border:1px solid var(--border);border-radius:7px;background:var(--white);color:var(--text);font-size:1rem;font-weight:700;cursor:pointer}
    .qty-control button:hover:not(:disabled){border-color:var(--red);color:var(--red);background:#FEF2F2}
    .qty-control button:focus-visible,.checkout-hapus:focus-visible,.checkout-head button:focus-visible,.checkout-foot button:focus-visible{outline:2px solid var(--red);outline-offset:2px}
    .qty-control button:disabled{color:#9CA3AF;background:var(--gray-100);cursor:not-allowed}
    .qty-control span{min-width:24px;text-align:center;font-size:.85rem;font-weight:700}
    .checkout-hapus{min-height:34px;border:0;background:none;color:var(--red);font-size:.75rem;font-weight:600;cursor:pointer}
    .checkout-hapus:hover{text-decoration:underline}
    .checkout-subtotal{font-size:.85rem;font-weight:700;white-space:nowrap}
    .checkout-kosong,.checkout-error{font-size:.82rem;color:var(--muted)}
    .checkout-error{color:#991B1B;margin:.5rem 0 0}
    .checkout-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
    .checkout-grid .form-group{margin:0}
    .checkout-catatan{grid-column:1 / -1}
    .checkout-total{display:flex;flex-direction:column;gap:.1rem}
    .checkout-total span:first-child{font-size:.75rem;color:var(--muted)}
    .checkout-total strong{font-size:1.2rem}
    .checkout-kembalian{font-size:.75rem;color:var(--muted);min-height:1rem}
    @media(max-width:900px){
        .kasir-tray{padding:.65rem 1rem}
        .checkout-modal{width:min(680px,calc(100% - 1rem));max-height:94dvh}
        .checkout-body{max-height:calc(94dvh - 155px)}
    }
    @media(max-width:600px){
        .kasir-panel{padding:.85rem}
        .kasir-toolbar{align-items:stretch;flex-direction:column;gap:.6rem}
        .kasir-search{max-width:none}
        .kasir-grid{grid-template-columns:repeat(auto-fill,minmax(145px,1fr));gap:.6rem}
        .menu-tile{min-height:128px;padding:.7rem}
        .kasir-tray{gap:.6rem;padding:calc(.6rem + env(safe-area-inset-bottom)) .75rem .6rem}
        .kasir-tray .btn-sm{min-width:150px;justify-content:center}
        .checkout-modal{width:100%;max-height:100dvh;border-radius:12px 12px 0 0;margin:auto 0 0}
        .checkout-head,.checkout-body,.checkout-foot{padding-left:.85rem;padding-right:.85rem}
        .checkout-body{max-height:calc(100dvh - 190px)}
        .checkout-item{grid-template-columns:minmax(0,1fr) auto;gap:.45rem}
        .checkout-item .qty-control{grid-column:1}
        .checkout-hapus,.checkout-subtotal{grid-column:2;justify-self:end}
        .qty-control button{width:44px;height:44px}
        .checkout-hapus{min-height:44px}
        .checkout-grid{grid-template-columns:1fr}
        .checkout-catatan{grid-column:auto}
        .checkout-foot{align-items:stretch;flex-direction:column}
        .checkout-foot .btn-sm{min-height:48px;justify-content:center}
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const form = document.getElementById('form-kasir');
    const hidden = document.getElementById('items');
    const grid = document.getElementById('kasir-grid');
    const tiles = Array.from(document.querySelectorAll('[data-menu]'));
    const cari = document.getElementById('cari-menu');
    const kosongkan = document.getElementById('kosongkan-cari');
    const jumlahMenu = document.getElementById('jumlah-menu');
    const noResult = document.getElementById('kasir-no-result');
    const trayItem = document.getElementById('tray-item');
    const trayTotal = document.getElementById('tray-total');
    const buka = document.getElementById('buka-checkout');
    const modal = document.getElementById('checkout-modal');
    const tutup = document.getElementById('tutup-checkout');
    const daftarItem = document.getElementById('checkout-items');
    const kosongItem = document.getElementById('checkout-kosong');
    const totalEl = document.getElementById('total-belanja');
    const kembalianEl = document.getElementById('kembalian');
    const errorEl = document.getElementById('checkout-error');
    const metode = document.getElementById('metode_pembayaran');
    const uang = document.getElementById('uang_diterima');
    const rupiah = (n) => 'Rp' + new Intl.NumberFormat('id-ID').format(n);
    const keranjang = new Map();

<<<<<<< HEAD
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

=======
    tiles.forEach((tile) => {
        const qty = Number(tile.dataset.qtyAwal || 0);
        if (qty > 0 && Number(tile.dataset.stok || 0) > 0) {
            keranjang.set(tile.dataset.id, {
                nama: tile.querySelector('.menu-tile-nama').textContent.trim(),
                harga: Number(tile.dataset.harga || 0),
                stok: Number(tile.dataset.stok || 0),
                qty: Math.min(qty, Number(tile.dataset.stok || 0)),
            });
        }
    });

    function total() {
        let hasil = 0;
        keranjang.forEach((item) => {
            hasil += item.harga * item.qty;
        });
        return hasil;
    }

    function tampilkanError(pesan) {
        errorEl.textContent = pesan || '';
        errorEl.hidden = !pesan;
    }

    function render() {
        const nilaiTotal = total();
        const jumlah = Array.from(keranjang.values()).reduce((akar, item) => akar + item.qty, 0);
        hidden.value = JSON.stringify(Array.from(keranjang, ([id, item]) => ({ id: Number(id), qty: item.qty })));
        trayItem.textContent = jumlah + ' item';
        trayTotal.textContent = rupiah(nilaiTotal);
        totalEl.textContent = rupiah(nilaiTotal);
        buka.disabled = jumlah === 0;
        buka.setAttribute('aria-expanded', modal.open ? 'true' : 'false');
        kosongItem.hidden = keranjang.size > 0;
        daftarItem.replaceChildren();

        keranjang.forEach((item, id) => {
            const baris = document.createElement('div');
            baris.className = 'checkout-item';
            baris.dataset.id = id;

            const info = document.createElement('div');
            const nama = document.createElement('div');
            nama.className = 'checkout-item-nama';
            nama.textContent = item.nama;
            const harga = document.createElement('div');
            harga.className = 'checkout-item-harga';
            harga.textContent = rupiah(item.harga) + ' per item';
            info.append(nama, harga);

            const kontrol = document.createElement('div');
            kontrol.className = 'qty-control';
            const kurang = document.createElement('button');
            kurang.type = 'button';
            kurang.dataset.ubah = '-1';
            kurang.setAttribute('aria-label', 'Kurangi ' + item.nama);
            kurang.textContent = '−';
            const qty = document.createElement('span');
            qty.textContent = item.qty;
            const tambah = document.createElement('button');
            tambah.type = 'button';
            tambah.dataset.ubah = '1';
            tambah.setAttribute('aria-label', 'Tambah ' + item.nama);
            tambah.textContent = '+';
            tambah.disabled = item.qty >= item.stok;
            kontrol.append(kurang, qty, tambah);

            const hapus = document.createElement('button');
            hapus.type = 'button';
            hapus.className = 'checkout-hapus';
            hapus.dataset.hapus = id;
            hapus.setAttribute('aria-label', 'Hapus ' + item.nama);
            hapus.textContent = 'Hapus';

            const subtotal = document.createElement('div');
            subtotal.className = 'checkout-subtotal';
            subtotal.textContent = rupiah(item.harga * item.qty);

            baris.append(info, kontrol, hapus, subtotal);
            daftarItem.append(baris);
        });

        if (metode.value === 'qris') {
            uang.value = nilaiTotal;
            uang.readOnly = true;
        }
        updateKembalian();
    }

    function updateKembalian() {
        const dibayar = Number(uang.value || 0);
        const hasil = total();
        kembalianEl.textContent = metode.value === 'tunai' && dibayar > hasil
            ? 'Kembalian ' + rupiah(dibayar - hasil)
            : '';
    }

    function filterMenu() {
        if (!grid) return;
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;
        tiles.forEach((tile) => {
            const cocok = tile.dataset.nama.includes(kata);
            tile.hidden = !cocok;
            if (cocok) tampil++;
        });
        jumlahMenu.textContent = kata === ''
            ? 'Menampilkan ' + tiles.length + ' menu'
            : 'Menampilkan ' + tampil + ' dari ' + tiles.length + ' menu';
        kosongkan.hidden = kata === '';
        noResult.hidden = tampil > 0;
    }

    tiles.forEach((tile) => {
        tile.addEventListener('click', () => {
            const id = tile.dataset.id;
            const item = keranjang.get(id) || {
                nama: tile.querySelector('.menu-tile-nama').textContent.trim(),
                harga: Number(tile.dataset.harga || 0),
                stok: Number(tile.dataset.stok || 0),
                qty: 0,
            };
            if (item.qty >= item.stok) {
                tampilkanError('Stok ' + item.nama + ' tersisa ' + item.stok + '.');
                return;
            }
            item.qty += 1;
            keranjang.set(id, item);
            tampilkanError('');
            render();
        });
    });

    daftarItem.addEventListener('click', (event) => {
        const ubah = event.target.closest('[data-ubah]');
        const hapus = event.target.closest('[data-hapus]');
        if (!ubah && !hapus) return;

        const baris = (ubah || hapus).closest('.checkout-item');
        const id = baris.dataset.id;
        const item = keranjang.get(id);
        if (!item) return;

        if (hapus) {
            keranjang.delete(id);
        } else {
            const next = item.qty + Number(ubah.dataset.ubah);
            if (next < 1) {
                keranjang.delete(id);
            } else if (next > item.stok) {
                tampilkanError('Stok ' + item.nama + ' tersisa ' + item.stok + '.');
                return;
            } else {
                item.qty = next;
                keranjang.set(id, item);
            }
        }
        tampilkanError('');
        render();
    });

    cari.addEventListener('input', filterMenu);
    kosongkan.addEventListener('click', () => {
        cari.value = '';
        filterMenu();
        cari.focus();
    });

    let uangTunaiSebelumnya = uang.value;
>>>>>>> 33dda617376f4c861e8d254db5bceaab96f61929
    metode.addEventListener('change', () => {
        if (metode.value === 'qris') {
            if (uang.readOnly === false) uangTunaiSebelumnya = uang.value;
            uang.value = total();
            uang.readOnly = true;
        } else {
            uang.readOnly = false;
            uang.value = uangTunaiSebelumnya || '';
        }
        uang.dispatchEvent(new Event('input'));
    });
    uang.addEventListener('input', updateKembalian);

    function bukaModal() {
        if (keranjang.size === 0) return;
        tampilkanError('');
        modal.showModal();
        render();
        tutup.focus();
    }

    function tutupModal() {
        if (modal.open) modal.close();
    }

    buka.addEventListener('click', bukaModal);
    tutup.addEventListener('click', tutupModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) tutupModal();
    });
    modal.addEventListener('close', () => {
        buka.setAttribute('aria-expanded', 'false');
        buka.focus();
    });

    form.addEventListener('submit', (event) => {
        const jumlah = Array.from(keranjang.values()).reduce((akar, item) => akar + item.qty, 0);
        if (jumlah === 0) {
            event.preventDefault();
            if (!modal.open) bukaModal();
            tampilkanError('Pilih minimal satu menu.');
            return;
        }
        const nilaiTotal = total();
        if (metode.value === 'qris') {
            uang.value = nilaiTotal;
            uang.readOnly = true;
        }
        const dibayar = Number(uang.value || 0);
        if (dibayar < nilaiTotal) {
            event.preventDefault();
            tampilkanError('Uang diterima kurang dari total belanja.');
            uang.focus();
            return;
        }
        hidden.value = JSON.stringify(Array.from(keranjang, ([id, item]) => ({ id: Number(id), qty: item.qty })));
    });

    filterMenu();
    render();
})();
</script>
@endpush
