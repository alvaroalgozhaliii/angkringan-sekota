@extends('admin.layouts.app')
@section('title', 'Kasir POS')
@section('page-title', 'Kasir')
@section('page-subtitle', 'Transaksi cepat, mudah, dan praktis')
@section('page-icon')
    <span style="color:var(--red);font-size:1.4rem;display:inline-flex;align-items:center">🛒</span>
@endsection

@push('styles')
<style>
    /* ── KASIR LAYOUT ── */
    .pos-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
        align-items: flex-start;
    }

    /* ── LEFT COLUMN: MENU & SEARCH ── */
    .pos-left {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .pos-toolbar {
        display: flex;
        align-items: center;
        gap: .85rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .search-box input {
        width: 100%;
        padding: .65rem 1rem .65rem 2.4rem;
        border: 1.5px solid var(--border);
        border-radius: 30px;
        font-size: .88rem;
        font-family: inherit;
        background: #fff;
        transition: all .2s;
    }
    .search-box input:focus {
        outline: none;
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(200,16,46,.1);
    }
    .search-box .search-icon {
        position: absolute;
        left: .9rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: .95rem;
    }

    /* Categories Tabs */
    .category-pills {
        display: flex;
        align-items: center;
        gap: .5rem;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .cat-pill {
        padding: .5rem 1.1rem;
        border-radius: 20px;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid transparent;
        background: #fff;
        color: var(--text);
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        white-space: nowrap;
        transition: all .2s;
    }
    .cat-pill:hover {
        border-color: #FECACA;
        color: var(--red);
    }
    .cat-pill.active {
        background: var(--red);
        color: #fff;
        box-shadow: 0 2px 6px rgba(200,16,46,.25);
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .product-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--border);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all .2s ease;
        cursor: pointer;
        box-shadow: 0 1px 4px rgba(0,0,0,.03);
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,.07);
        border-color: #FECACA;
    }
    .product-img-wrap {
        width: 100%;
        height: 125px;
        background: #F3F4F6;
        overflow: hidden;
        position: relative;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .3s;
    }
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    .product-info {
        padding: .85rem .95rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
    }
    .product-name {
        font-size: .88rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: .4rem;
        line-height: 1.25;
    }
    .product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: .4rem;
    }
    .product-price {
        font-size: .95rem;
        font-weight: 800;
        color: var(--red);
    }
    .btn-add-item {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--red);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: transform .15s, background .15s;
    }
    .product-card:hover .btn-add-item {
        background: var(--red-dark);
        transform: scale(1.1);
    }

    /* ── RIGHT COLUMN: CART ── */
    .pos-cart {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        position: sticky;
        top: 85px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 105px);
    }
    .cart-header {
        background: var(--red);
        color: #fff;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cart-title {
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: .02em;
    }
    .btn-clear-cart {
        background: transparent;
        border: none;
        color: #fff;
        font-size: .8rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .3rem;
        opacity: .9;
        transition: opacity .2s;
    }
    .btn-clear-cart:hover {
        opacity: 1;
        text-decoration: underline;
    }

    /* Cart Body */
    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.25rem;
        min-height: 220px;
    }
    .cart-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 220px;
        text-align: center;
        color: var(--muted);
        padding: 1.5rem 1rem;
    }
    .cart-empty-icon {
        font-size: 3.5rem;
        margin-bottom: .6rem;
        color: #D1D5DB;
    }
    .cart-empty-title {
        font-weight: 700;
        font-size: .95rem;
        color: #4B5563;
        margin-bottom: .25rem;
    }
    .cart-empty-sub {
        font-size: .78rem;
        color: #9CA3AF;
        line-height: 1.4;
        max-width: 220px;
    }

    /* Cart Items List */
    .cart-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-item-details {
        flex: 1;
        min-width: 0;
    }
    .cart-item-name {
        font-size: .85rem;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cart-item-price {
        font-size: .78rem;
        color: var(--muted);
        margin-top: 1px;
    }
    .cart-qty-control {
        display: flex;
        align-items: center;
        gap: .35rem;
    }
    .btn-qty {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text);
        font-size: .85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-qty:hover {
        background: #FEE2E2;
        border-color: #FECACA;
        color: var(--red);
    }
    .cart-qty-num {
        font-size: .85rem;
        font-weight: 700;
        min-width: 22px;
        text-align: center;
    }
    .cart-item-subtotal {
        font-size: .85rem;
        font-weight: 700;
        color: var(--red);
        text-align: right;
        min-width: 70px;
    }
    .btn-remove-item {
        background: none;
        border: none;
        color: #9CA3AF;
        cursor: pointer;
        font-size: 1rem;
        padding: 4px;
        transition: color .2s;
    }
    .btn-remove-item:hover {
        color: #EF4444;
    }

    /* Cart Footer */
    .cart-footer {
        padding: 1.1rem 1.25rem;
        border-top: 1px solid var(--border);
        background: #FAFAFA;
    }
    .total-banner {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 10px;
        padding: .85rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .total-label {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text);
    }
    .total-amount {
        font-size: 1.55rem;
        font-weight: 900;
        color: var(--red);
    }
    .cart-actions {
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: .65rem;
    }
    .btn-receipt {
        background: #fff;
        border: 1.5px solid var(--border);
        color: var(--text);
        padding: .75rem .8rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-receipt:hover:not(:disabled) {
        border-color: var(--red);
        color: var(--red);
    }
    .btn-receipt:disabled {
        opacity: .5;
        cursor: not-allowed;
    }
    .btn-checkout {
        background: var(--red);
        border: none;
        color: #fff;
        padding: .75rem 1rem;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 10px rgba(200,16,46,.25);
    }
    .btn-checkout:hover:not(:disabled) {
        background: var(--red-dark);
        transform: translateY(-1px);
    }
    .btn-checkout:disabled {
        opacity: .5;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* ── HISTORY PANEL AT BOTTOM ── */
    .history-panel {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--border);
        padding: 1.25rem 1.4rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.03);
        margin-top: 1rem;
    }
    .history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: .6rem;
        border-bottom: 1px solid var(--border);
    }
    .history-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .history-more {
        font-size: .8rem;
        font-weight: 600;
        color: var(--red);
        text-decoration: none;
    }
    .history-more:hover {
        text-decoration: underline;
    }

    /* ── MODALS ── */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(2px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-backdrop.open {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        max-width: 500px;
        width: 100%;
        max-height: 92vh;
        overflow-y: auto;
        box-shadow: 0 20px 30px rgba(0,0,0,.2);
        padding: 1.5rem;
        animation: modalScale .2s ease-out;
    }
    @keyframes modalScale {
        from { opacity: 0; transform: scale(.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #FEE2E2;
        padding-bottom: .8rem;
        margin-bottom: 1.2rem;
    }
    .modal-header-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--red);
    }
    .modal-close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
        line-height: 1;
    }

    /* Method Selector Pills */
    .method-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .method-tab {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: .85rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        font-weight: 700;
        font-size: .9rem;
    }
    .method-tab.active {
        border-color: var(--red);
        background: #FEF2F2;
        color: var(--red);
    }

    /* Quick Cash Suggestion Pills */
    .quick-cash-row {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        margin-top: .5rem;
    }
    .quick-cash-btn {
        background: #F3F4F6;
        border: 1px solid var(--border);
        padding: .35rem .65rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
    }
    .quick-cash-btn:hover {
        background: #FEE2E2;
        color: var(--red);
        border-color: #FECACA;
    }

    /* Mobile Floating Cart Bar */
    .mobile-cart-bar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid var(--border);
        padding: .75rem 1rem;
        box-shadow: 0 -4px 15px rgba(0,0,0,.08);
        z-index: 45;
        align-items: center;
        justify-content: space-between;
    }

    @media (max-width: 1200px) {
        .product-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 992px) {
        .pos-container {
            grid-template-columns: 1fr;
        }
        .pos-cart {
            display: none; /* hidden on small screen unless opened in modal */
        }
        .pos-cart.drawer-open {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1050;
            border-radius: 0;
            max-height: 100vh;
        }
        .mobile-cart-bar {
            display: flex;
        }
    }
    @media (max-width: 600px) {
        .product-grid { grid-template-columns: repeat(2, 1fr); }
        .product-img-wrap { height: 105px; }
    }
</style>
@endpush

@section('content')
<div class="pos-container">

    {{-- ── LEFT COLUMN: SEARCH, CATEGORIES, PRODUCT GRID, TODAY HISTORY ── --}}
    <div class="pos-left">

        {{-- Toolbar: Search & Categories --}}
        <div class="pos-toolbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari menu...">
            </div>
            <div class="category-pills" id="categoryContainer">
                <button type="button" class="cat-pill active" data-category="all">Semua</button>
                @foreach($categories as $cat)
                    <button type="button" class="cat-pill" data-category="{{ strtolower($cat) }}">{{ $cat }}</button>
                @endforeach
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="product-grid" id="productGrid">
            @forelse($produk as $p)
                <div class="product-card" 
                     data-id="{{ $p->id }}" 
                     data-nama="{{ $p->nama }}" 
                     data-harga="{{ $p->harga }}" 
                     data-kategori="{{ strtolower($p->kategori) }}"
                     onclick="tambahKeKeranjang({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->harga }}, '{{ $p->gambar_url }}')">
                    <div class="product-img-wrap">
                        <img src="{{ $p->gambar_url }}" alt="{{ $p->nama }}" class="product-img" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $p->nama }}</div>
                        <div class="product-meta">
                            <span class="product-price">{{ $p->harga_format }}</span>
                            <button type="button" class="btn-add-item" title="Tambah ke keranjang" onclick="event.stopPropagation(); tambahKeKeranjang({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->harga }}, '{{ $p->gambar_url }}')">
                                +
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background:#fff; border-radius:12px; padding:3rem; text-align:center; color:var(--muted)">
                    <div style="font-size:2rem;margin-bottom:.5rem">🍽️</div>
                    <strong>Belum ada menu produk yang tayang.</strong>
                    <p style="font-size:.85rem;margin-top:.25rem">Tambahkan produk di menu Menu &amp; Produk terlebih dahulu.</p>
                </div>
            @endforelse
        </div>

        {{-- Riwayat Transaksi Hari Ini --}}
        <div class="history-panel">
            <div class="history-header">
                <div class="history-title">
                    <span>🕒</span>
                    <span>Riwayat Transaksi Hari Ini</span>
                </div>
                <a href="{{ route('admin.kasir.riwayat') }}" class="history-more">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="todayHistoryBody">
                        @forelse($transaksiHariIni as $trx)
                            <tr>
                                <td><strong style="color:var(--text);font-family:monospace">{{ $trx->nomor_transaksi }}</strong></td>
                                <td>{{ $trx->formatted_waktu }}</td>
                                <td><strong style="color:var(--red)">{{ $trx->formatted_total }}</strong></td>
                                <td style="text-align:center">
                                    <button type="button" class="btn-sm btn-white" style="padding:.25rem .6rem;font-size:.75rem" onclick="bukaDetailTrx({{ $trx->id }})">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyHistoryRow">
                                <td colspan="4" style="text-align:center;color:var(--muted);padding:1.5rem">
                                    Belum ada transaksi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN: CART ── --}}
    <div class="pos-cart" id="posCart">
        <div class="cart-header">
            <div class="cart-title">Keranjang Pesanan</div>
            <button type="button" class="btn-clear-cart" onclick="kosongkanKeranjang()" title="Kosongkan Keranjang">
                <span>🗑</span> Kosongkan
            </button>
        </div>

        {{-- List Items / Empty State --}}
        <div class="cart-body" id="cartBody">
            <div class="cart-empty" id="cartEmptyState">
                <div class="cart-empty-icon">
                    <svg width="70" height="70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <div class="cart-empty-title">Belum ada pesanan</div>
                <div class="cart-empty-sub">Pilih menu di sebelah kiri untuk menambahkan pesanan</div>
            </div>
            <div id="cartItemsList"></div>
        </div>

        {{-- Footer & Checkout --}}
        <div class="cart-footer">
            <div class="total-banner">
                <span class="total-label">Total</span>
                <span class="total-amount" id="cartTotalDisplay">Rp0</span>
            </div>
            <div class="cart-actions">
                <button type="button" class="btn-receipt" id="btnPrintLastReceipt" onclick="cetakStrukTerakhir()" disabled title="Cetak struk transaksi terakhir">
                    <span>🖨</span> Cetak Struk
                </button>
                <button type="button" class="btn-checkout" id="btnCheckout" onclick="bukaModalPembayaran()" disabled>
                    <span>💳</span> Proses Pembayaran
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ── MOBILE FLOATING CART BAR ── --}}
<div class="mobile-cart-bar" id="mobileCartBar">
    <div>
        <div style="font-size:.75rem;color:var(--muted)"><span id="mobileItemCount">0</span> Menu Dipilih</div>
        <div style="font-size:1.1rem;font-weight:800;color:var(--red)" id="mobileCartTotal">Rp0</div>
    </div>
    <button type="button" class="btn-sm btn-red" onclick="toggleMobileCartDrawer()" style="padding:.6rem 1.2rem">
        🛒 Buka Keranjang
    </button>
</div>

{{-- ── MODAL PROSES PEMBAYARAN ── --}}
<div class="modal-backdrop" id="modalPembayaran">
    <div class="modal-box">
        <div class="modal-header-row">
            <div class="modal-header-title">Proses Pembayaran</div>
            <button type="button" class="modal-close-btn" onclick="tutupModalPembayaran()">&times;</button>
        </div>

        {{-- Total display --}}
        <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.9rem 1rem;display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem">
            <span style="font-size:.9rem;color:var(--text);font-weight:600">Total Tagihan:</span>
            <span style="font-size:1.45rem;font-weight:900;color:var(--red)" id="modalPayTotal">Rp0</span>
        </div>

        {{-- Payment Method Tabs --}}
        <div class="method-selector">
            <div class="method-tab active" id="tabTunai" onclick="pilihMetode('tunai')">
                💵 Tunai (Cash)
            </div>
            <div class="method-tab" id="tabQris" onclick="pilihMetode('qris')">
                📱 QRIS
            </div>
        </div>

        <form id="paymentForm" onsubmit="submitTransaksi(event)">
            {{-- Bagian Tunai --}}
            <div id="sectionTunai">
                <div class="form-group">
                    <label class="form-label">Uang Diterima (Rp)</label>
                    <input type="number" id="inputUangDiterima" class="form-control" placeholder="Contoh: 50000" min="0" oninput="hitungKembalian()">
                    
                    {{-- Quick cash buttons --}}
                    <div class="quick-cash-row" id="quickCashButtons"></div>
                </div>

                <div id="kembalianBox" style="background:#F9FAFB;border:1px solid var(--border);border-radius:8px;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                    <span style="font-size:.85rem;font-weight:600">Kembalian:</span>
                    <strong style="font-size:1.15rem;color:#059669" id="kembalianText">Rp0</strong>
                </div>

                <div id="warningUangKurang" style="display:none;background:#FEE2E2;border:1px solid #FECACA;color:#991B1B;padding:.6rem .85rem;border-radius:8px;font-size:.8rem;margin-bottom:1rem">
                    ⚠️ Uang diterima masih kurang dari total tagihan!
                </div>
            </div>

            {{-- Bagian QRIS --}}
            <div id="sectionQris" style="display:none;margin-bottom:1rem">
                <div style="background:#F9FAFB;border:1px solid var(--border);border-radius:12px;padding:1.2rem;text-align:center">
                    <div style="font-weight:700;font-size:.95rem;color:var(--text);margin-bottom:.4rem">
                        QRIS Angkringan SEKOTA
                    </div>
                    <p style="font-size:.76rem;color:var(--muted);margin-bottom:1rem">
                        Pindai QRIS ini melalui GoPay, OVO, Dana, BCA, atau Mobile Banking lainnya.
                    </p>

                    {{-- QR Code Display --}}
                    <div style="display:inline-block;padding:10px;background:#fff;border:1.5px solid var(--border);border-radius:12px;box-shadow:0 2px 6px rgba(0,0,0,.05);margin-bottom:.8rem">
                        <svg width="180" height="180" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Background QR -->
                            <rect width="200" height="200" fill="white"/>
                            <!-- Outer Position Markers -->
                            <rect x="20" y="20" width="45" height="45" rx="6" fill="#C8102E"/>
                            <rect x="26" y="26" width="33" height="33" rx="3" fill="white"/>
                            <rect x="32" y="32" width="21" height="21" rx="2" fill="#C8102E"/>

                            <rect x="135" y="20" width="45" height="45" rx="6" fill="#C8102E"/>
                            <rect x="141" y="26" width="33" height="33" rx="3" fill="white"/>
                            <rect x="147" y="32" width="21" height="21" rx="2" fill="#C8102E"/>

                            <rect x="20" y="135" width="45" height="45" rx="6" fill="#C8102E"/>
                            <rect x="26" y="141" width="33" height="33" rx="3" fill="white"/>
                            <rect x="32" y="147" width="21" height="21" rx="2" fill="#C8102E"/>

                            <!-- QR Grid Patterns -->
                            <rect x="75" y="25" width="10" height="10" fill="#1F2937"/>
                            <rect x="95" y="25" width="20" height="10" fill="#1F2937"/>
                            <rect x="75" y="45" width="20" height="10" fill="#1F2937"/>
                            <rect x="105" y="45" width="10" height="20" fill="#1F2937"/>
                            <rect x="85" y="75" width="30" height="15" fill="#1F2937"/>
                            <rect x="25" y="75" width="15" height="25" fill="#1F2937"/>
                            <rect x="50" y="85" width="15" height="25" fill="#1F2937"/>
                            <rect x="135" y="75" width="20" height="15" fill="#1F2937"/>
                            <rect x="165" y="85" width="15" height="25" fill="#1F2937"/>
                            <rect x="85" y="100" width="30" height="30" rx="4" fill="#C8102E"/>
                            <text x="100" y="120" font-family="Poppins, sans-serif" font-weight="900" font-size="14" fill="white" text-anchor="middle">SEKOTA</text>
                            <rect x="75" y="140" width="20" height="15" fill="#1F2937"/>
                            <rect x="105" y="140" width="15" height="20" fill="#1F2937"/>
                            <rect x="135" y="135" width="15" height="25" fill="#1F2937"/>
                            <rect x="160" y="145" width="20" height="20" fill="#1F2937"/>
                            <rect x="135" y="170" width="35" height="10" fill="#1F2937"/>
                        </svg>
                    </div>

                    <div style="font-size:.82rem;font-weight:700;color:var(--text);margin-bottom:.75rem">
                        Nominal Pas: <span style="color:var(--red)" id="qrisTotalText">Rp0</span>
                    </div>

                    {{-- Confirmation Checkbox by Employee (Requirement 6.C) --}}
                    <label style="display:flex;align-items:flex-start;gap:.55rem;text-align:left;background:#fff;border:1.5px solid #FCD34D;background:#FFFBEB;border-radius:8px;padding:.75rem;cursor:pointer">
                        <input type="checkbox" id="checkKonfirmasiQris" onchange="validasiQris()" style="margin-top:2px">
                        <span style="font-size:.76rem;color:#92400E;line-height:1.35">
                            <strong>Konfirmasi Kasir:</strong> Saya telah memeriksa langsung bahwa dana telah BERHASIL masuk ke notifikasi / rekening toko.
                        </span>
                    </label>
                </div>
            </div>

            {{-- Optional Info --}}
            <div class="two-col" style="gap:.75rem;margin-bottom:1rem">
                <div>
                    <label class="form-label" style="font-size:.76rem">Nama Pelanggan (Opsional)</label>
                    <input type="text" id="inputNamaPelanggan" class="form-control" placeholder="Contoh: Meja 3 / Mas Budi">
                </div>
                <div>
                    <label class="form-label" style="font-size:.76rem">Catatan Pesanan (Opsional)</label>
                    <input type="text" id="inputCatatan" class="form-control" placeholder="Contoh: Kurang manis / Tanpa es">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.75rem;border-top:1px solid var(--border);padding-top:1rem">
                <button type="button" class="btn-sm btn-white" onclick="tutupModalPembayaran()">Batalkan</button>
                <button type="submit" class="btn-sm btn-red" id="btnSubmitBayar" style="padding:.7rem 1.4rem">
                    Konfirmasi &amp; Selesaikan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL TRANSAKSI BERHASIL (CETAK STRUK) ── --}}
<div class="modal-backdrop" id="modalSukses">
    <div class="modal-box" style="text-align:center">
        <div style="width:65px;height:65px;border-radius:50%;background:#D1FAE5;color:#059669;font-size:2rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
            ✓
        </div>
        <h3 style="font-size:1.3rem;font-weight:800;color:var(--text);margin-bottom:.3rem">Transaksi Berhasil!</h3>
        <p style="font-size:.82rem;color:var(--muted);margin-bottom:1.25rem">Pembayaran telah disimpan ke sistem.</p>

        <div style="background:#F9FAFB;border:1px solid var(--border);border-radius:10px;padding:1rem;margin-bottom:1.5rem;text-align:left;font-size:.82rem">
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
                <span style="color:var(--muted)">No. Transaksi:</span>
                <strong style="font-family:monospace;color:var(--red)" id="suksesNoTrx">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
                <span style="color:var(--muted)">Metode:</span>
                <span id="suksesMetode">-</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
                <span style="color:var(--muted)">Total:</span>
                <strong id="suksesTotal" style="color:var(--text)">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:.4rem" id="suksesKembalianRow">
                <span style="color:var(--muted)">Kembalian:</span>
                <strong id="suksesKembalian" style="color:#059669">-</strong>
            </div>
        </div>

        {{-- 3 Main Buttons as per requirement 7 --}}
        <div style="display:flex;flex-direction:column;gap:.65rem">
            <button type="button" class="btn-sm btn-red" onclick="cetakStrukModalSukses()" style="justify-content:center;padding:.75rem">
                🖨 Cetak Struk
            </button>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem">
                <button type="button" class="btn-sm btn-white" onclick="lihatDetailModalSukses()" style="justify-content:center;padding:.65rem">
                    📄 Lihat Detail
                </button>
                <button type="button" class="btn-sm btn-outline-red" onclick="transaksiBaru()" style="justify-content:center;padding:.65rem">
                    ➕ Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL DETAIL TRANSAKSI ── --}}
<div class="modal-backdrop" id="modalDetailTrx">
    <div class="modal-box">
        <div class="modal-header-row">
            <div class="modal-header-title" id="detailModalTitle">Detail Transaksi</div>
            <button type="button" class="modal-close-btn" onclick="tutupDetailTrx()">&times;</button>
        </div>
        <div id="detailModalContent"></div>
        <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;gap:.75rem;border-top:1px solid var(--border);padding-top:1rem">
            <button type="button" class="btn-sm btn-white" onclick="tutupDetailTrx()">Tutup</button>
            <a id="detailPrintLink" href="#" target="_blank" class="btn-sm btn-red">🖨 Cetak Struk</a>
        </div>
    </div>
</div>

{{-- Hidden Iframe for Direct Silent Receipt Printing --}}
<iframe id="printIframe" style="display:none;position:absolute;width:0;height:0;border:0;"></iframe>
@endsection

@push('scripts')
<script>
    // State Keranjang
    let cart = [];
    let activeCategory = 'all';
    let searchQuery = '';
    let selectedMetode = 'tunai';
    let lastCompletedTransactionId = null;

    // Elements
    const cartBody           = document.getElementById('cartBody');
    const cartEmptyState     = document.getElementById('cartEmptyState');
    const cartItemsList      = document.getElementById('cartItemsList');
    const cartTotalDisplay   = document.getElementById('cartTotalDisplay');
    const btnCheckout        = document.getElementById('btnCheckout');
    const btnPrintLastReceipt= document.getElementById('btnPrintLastReceipt');
    const mobileCartTotal    = document.getElementById('mobileCartTotal');
    const mobileItemCount    = document.getElementById('mobileItemCount');

    // ── SEARCH & FILTER MENU ──
    const searchInput = document.getElementById('searchInput');
    searchInput?.addEventListener('input', (e) => {
        searchQuery = e.target.value.toLowerCase().trim();
        filterMenuDisplay();
    });

    const categoryContainer = document.getElementById('categoryContainer');
    categoryContainer?.addEventListener('click', (e) => {
        if (e.target.classList.contains('cat-pill')) {
            document.querySelectorAll('.cat-pill').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
            activeCategory = e.target.getAttribute('data-category');
            filterMenuDisplay();
        }
    });

    function filterMenuDisplay() {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            const nama = card.getAttribute('data-nama').toLowerCase();
            const kategori = card.getAttribute('data-kategori');

            const matchSearch = nama.includes(searchQuery);
            const matchCategory = activeCategory === 'all' || kategori === activeCategory;

            if (matchSearch && matchCategory) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // ── KERANJANG OPERATIONS ──
    function tambahKeKeranjang(id, nama, harga, gambar) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                id: id,
                nama: nama,
                harga: parseFloat(harga),
                qty: 1,
                gambar: gambar
            });
        }
        renderKeranjang();
    }

    function ubahQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        item.qty += delta;
        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        }
        renderKeranjang();
    }

    function hapusItem(id) {
        cart = cart.filter(i => i.id !== id);
        renderKeranjang();
    }

    function kosongkanKeranjang() {
        if (cart.length === 0) return;
        if (confirm('Kosongkan semua pesanan di keranjang?')) {
            cart = [];
            renderKeranjang();
        }
    }

    function hitungTotal() {
        return cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function renderKeranjang() {
        if (cart.length === 0) {
            cartEmptyState.style.display = 'flex';
            cartItemsList.innerHTML = '';
            cartTotalDisplay.textContent = 'Rp0';
            btnCheckout.disabled = true;
            mobileCartTotal.textContent = 'Rp0';
            mobileItemCount.textContent = '0';
            return;
        }

        cartEmptyState.style.display = 'none';
        btnCheckout.disabled = false;

        let total = hitungTotal();
        let totalQty = cart.reduce((sum, i) => sum + i.qty, 0);

        cartTotalDisplay.textContent = formatRupiah(total);
        mobileCartTotal.textContent = formatRupiah(total);
        mobileItemCount.textContent = totalQty;

        let html = '';
        cart.forEach(item => {
            const subtotal = item.harga * item.qty;
            html += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <div class="cart-item-name" title="${item.nama}">${item.nama}</div>
                        <div class="cart-item-price">${formatRupiah(item.harga)}</div>
                    </div>
                    <div class="cart-qty-control">
                        <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, -1)">-</button>
                        <span class="cart-qty-num">${item.qty}</span>
                        <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, 1)">+</button>
                    </div>
                    <div class="cart-item-subtotal">${formatRupiah(subtotal)}</div>
                    <button type="button" class="btn-remove-item" onclick="hapusItem(${item.id})" title="Hapus menu">&times;</button>
                </div>
            `;
        });
        cartItemsList.innerHTML = html;
    }

    // ── MOBILE DRAWER TOGGLE ──
    function toggleMobileCartDrawer() {
        const cartEl = document.getElementById('posCart');
        cartEl.classList.toggle('drawer-open');
    }

    // ── MODAL PEMBAYARAN ──
    const modalPembayaran   = document.getElementById('modalPembayaran');
    const modalPayTotal     = document.getElementById('modalPayTotal');
    const tabTunai          = document.getElementById('tabTunai');
    const tabQris           = document.getElementById('tabQris');
    const sectionTunai      = document.getElementById('sectionTunai');
    const sectionQris       = document.getElementById('sectionQris');
    const inputUangDiterima = document.getElementById('inputUangDiterima');
    const kembalianText     = document.getElementById('kembalianText');
    const warningUangKurang = document.getElementById('warningUangKurang');
    const btnSubmitBayar    = document.getElementById('btnSubmitBayar');
    const quickCashButtons  = document.getElementById('quickCashButtons');
    const qrisTotalText     = document.getElementById('qrisTotalText');
    const checkKonfirmasiQris = document.getElementById('checkKonfirmasiQris');

    function bukaModalPembayaran() {
        if (cart.length === 0) return;
        const total = hitungTotal();
        modalPayTotal.textContent = formatRupiah(total);
        qrisTotalText.textContent = formatRupiah(total);

        // Reset inputs
        inputUangDiterima.value = '';
        kembalianText.textContent = 'Rp0';
        warningUangKurang.style.display = 'none';
        if (checkKonfirmasiQris) checkKonfirmasiQris.checked = false;

        // Render quick cash suggestions
        renderQuickCashButtons(total);

        pilihMetode('tunai');
        modalPembayaran.classList.add('open');
        inputUangDiterima.focus();
    }

    function tutupModalPembayaran() {
        modalPembayaran.classList.remove('open');
    }

    function pilihMetode(metode) {
        selectedMetode = metode;
        if (metode === 'tunai') {
            tabTunai.classList.add('active');
            tabQris.classList.remove('active');
            sectionTunai.style.display = 'block';
            sectionQris.style.display = 'none';
            hitungKembalian();
        } else {
            tabQris.classList.add('active');
            tabTunai.classList.remove('active');
            sectionQris.style.display = 'block';
            sectionTunai.style.display = 'none';
            validasiQris();
        }
    }

    function renderQuickCashButtons(total) {
        const nominalList = [total, 10000, 20000, 50000, 100000];
        const uniqueList = Array.from(new Set(nominalList)).sort((a,b) => a - b);

        let html = '';
        uniqueList.forEach(nom => {
            const label = nom === total ? `Uang Pas (${formatRupiah(nom)})` : formatRupiah(nom);
            html += `<button type="button" class="quick-cash-btn" onclick="setUangDiterima(${nom})">${label}</button>`;
        });
        quickCashButtons.innerHTML = html;
    }

    function setUangDiterima(nominal) {
        inputUangDiterima.value = nominal;
        hitungKembalian();
    }

    function hitungKembalian() {
        const total = hitungTotal();
        const bayar = parseFloat(inputUangDiterima.value) || 0;
        const diff = bayar - total;

        if (bayar <= 0) {
            kembalianText.textContent = 'Rp0';
            warningUangKurang.style.display = 'none';
            btnSubmitBayar.disabled = true;
            return;
        }

        if (diff < 0) {
            warningUangKurang.style.display = 'block';
            warningUangKurang.textContent = `⚠️ Uang kurang ${formatRupiah(Math.abs(diff))}`;
            kembalianText.textContent = 'Rp0';
            btnSubmitBayar.disabled = true;
        } else {
            warningUangKurang.style.display = 'none';
            kembalianText.textContent = formatRupiah(diff);
            btnSubmitBayar.disabled = false;
        }
    }

    function validasiQris() {
        if (selectedMetode === 'qris') {
            btnSubmitBayar.disabled = !checkKonfirmasiQris.checked;
        }
    }

    // ── SUBMIT TRANSAKSI KE SERVER ──
    function submitTransaksi(event) {
        event.preventDefault();
        if (cart.length === 0) return;

        const total = hitungTotal();
        let uangDiterima = 0;

        if (selectedMetode === 'tunai') {
            uangDiterima = parseFloat(inputUangDiterima.value) || 0;
            if (uangDiterima < total) {
                alert('Uang yang diterima kurang dari total pembayaran.');
                return;
            }
        } else {
            if (!checkKonfirmasiQris.checked) {
                alert('Mohon konfirmasi pembayaran QRIS terlebih dahulu.');
                return;
            }
            uangDiterima = total;
        }

        btnSubmitBayar.disabled = true;
        btnSubmitBayar.innerHTML = 'Memproses...';

        const payload = {
            items: cart.map(i => ({ id: i.id, qty: i.qty })),
            metode_pembayaran: selectedMetode,
            uang_diterima: uangDiterima,
            nama_pelanggan: document.getElementById('inputNamaPelanggan').value,
            catatan: document.getElementById('inputCatatan').value,
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('admin.kasir.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmitBayar.disabled = false;
            btnSubmitBayar.innerHTML = 'Konfirmasi & Selesaikan';

            if (!data.success) {
                alert(data.message || 'Gagal menyimpan transaksi.');
                return;
            }

            // Sukses!
            tutupModalPembayaran();
            lastCompletedTransactionId = data.transaksi.id;
            btnPrintLastReceipt.disabled = false;

            // Tambahkan transaksi ke tabel riwayat hari ini di halaman secara real-time
            tambahRowRiwayatHariIni(data.transaksi);

            // Buka Modal Sukses
            bukaModalSukses(data.transaksi);

            // Reset cart
            cart = [];
            renderKeranjang();
        })
        .catch(err => {
            btnSubmitBayar.disabled = false;
            btnSubmitBayar.innerHTML = 'Konfirmasi & Selesaikan';
            alert('Terjadi kesalahan jaringan atau server. Silakan coba lagi.');
        });
    }

    function tambahRowRiwayatHariIni(trx) {
        const body = document.getElementById('todayHistoryBody');
        const emptyRow = document.getElementById('emptyHistoryRow');
        if (emptyRow) emptyRow.remove();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong style="color:var(--text);font-family:monospace">${trx.nomor_transaksi}</strong></td>
            <td>${trx.waktu}</td>
            <td><strong style="color:var(--red)">${trx.total_format}</strong></td>
            <td style="text-align:center">
                <button type="button" class="btn-sm btn-white" style="padding:.25rem .6rem;font-size:.75rem" onclick="bukaDetailTrx(${trx.id})">
                    Detail
                </button>
            </td>
        `;
        body.insertBefore(tr, body.firstChild);
    }

    // ── MODAL SUKSES & CETAK STRUK ──
    const modalSukses      = document.getElementById('modalSukses');
    const suksesNoTrx      = document.getElementById('suksesNoTrx');
    const suksesMetode     = document.getElementById('suksesMetode');
    const suksesTotal      = document.getElementById('suksesTotal');
    const suksesKembalian  = document.getElementById('suksesKembalian');
    const suksesKembalianRow = document.getElementById('suksesKembalianRow');

    function bukaModalSukses(trx) {
        suksesNoTrx.textContent = trx.nomor_transaksi;
        suksesMetode.textContent = trx.metode_pembayaran;
        suksesTotal.textContent = trx.total_format;

        if (trx.metode_pembayaran === 'TUNAI') {
            suksesKembalianRow.style.display = 'flex';
            suksesKembalian.textContent = trx.kembalian_fmt;
        } else {
            suksesKembalianRow.style.display = 'none';
        }

        modalSukses.classList.add('open');
    }

    function tutupModalSukses() {
        modalSukses.classList.remove('open');
    }

    function transaksiBaru() {
        tutupModalSukses();
        cart = [];
        renderKeranjang();
    }

    function cetakStrukModalSukses() {
        if (!lastCompletedTransactionId) return;
        cetakStrukById(lastCompletedTransactionId);
    }

    function cetakStrukTerakhir() {
        if (!lastCompletedTransactionId) {
            alert('Belum ada transaksi terakhir untuk dicetak.');
            return;
        }
        cetakStrukById(lastCompletedTransactionId);
    }

    function cetakStrukById(id) {
        const iframe = document.getElementById('printIframe');
        iframe.src = `/admin/kasir/struk/${id}?autoprint=1`;
    }

    function lihatDetailModalSukses() {
        if (!lastCompletedTransactionId) return;
        tutupModalSukses();
        bukaDetailTrx(lastCompletedTransactionId);
    }

    // ── MODAL DETAIL TRANSAKSI ──
    const modalDetailTrx   = document.getElementById('modalDetailTrx');
    const detailModalTitle = document.getElementById('detailModalTitle');
    const detailModalContent = document.getElementById('detailModalContent');
    const detailPrintLink  = document.getElementById('detailPrintLink');

    function bukaDetailTrx(id) {
        modalDetailTrx.classList.add('open');
        detailModalContent.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--muted)">Memuat rincian...</div>';
        detailPrintLink.href = `/admin/kasir/struk/${id}`;

        fetch(`/admin/kasir/transaksi/${id}`)
            .then(res => res.json())
            .then(data => {
                detailModalTitle.textContent = `Detail: ${data.nomor_transaksi}`;
                let html = `
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;background:#F9FAFB;padding:.85rem;border-radius:8px;margin-bottom:1rem;border:1px solid var(--border);font-size:.82rem">
                        <div><strong style="color:var(--muted);font-size:.75rem">WAKTU</strong><div>${data.tanggal_waktu}</div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">KASIR</strong><div>${data.nama_kasir}</div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">METODE</strong><div><span class="badge ${data.metode_pembayaran === 'TUNAI' ? 'badge-yellow' : 'badge-green'}">${data.metode_pembayaran}</span></div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">PELANGGAN</strong><div>${data.nama_pelanggan || '-'}</div></div>
                    </div>

                    <strong style="display:block;margin-bottom:.5rem;color:var(--text);font-size:.85rem">Item Pesanan:</strong>
                    <table style="width:100%;margin-bottom:1rem;border-collapse:collapse;font-size:.82rem">
                        <thead>
                            <tr style="background:#F3F4F6">
                                <th style="padding:.5rem;text-align:left">Menu</th>
                                <th style="padding:.5rem;text-align:center">Qty</th>
                                <th style="padding:.5rem;text-align:right">Harga</th>
                                <th style="padding:.5rem;text-align:right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.details.forEach(item => {
                    html += `
                        <tr style="border-bottom:1px solid #E5E7EB">
                            <td style="padding:.5rem">${item.nama}</td>
                            <td style="padding:.5rem;text-align:center">${item.jumlah}</td>
                            <td style="padding:.5rem;text-align:right">${item.harga_fmt}</td>
                            <td style="padding:.5rem;text-align:right;font-weight:600">${item.subtotal_fmt}</td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>

                    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:.85rem;font-size:.85rem">
                        <div style="display:flex;justify-content:space-between;margin-bottom:.3rem">
                            <span>Total Tagihan:</span>
                            <strong style="color:var(--red);font-size:1.1rem">${data.total_format}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:.3rem;color:var(--muted)">
                            <span>Uang Diterima:</span>
                            <span>${data.uang_diterima_fmt}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;color:var(--muted)">
                            <span>Kembalian:</span>
                            <span>${data.kembalian_fmt}</span>
                        </div>
                    </div>
                `;

                if (data.catatan) {
                    html += `<div style="margin-top:.75rem;font-size:.78rem;color:var(--muted)"><strong>Catatan:</strong> ${data.catatan}</div>`;
                }

                detailModalContent.innerHTML = html;
            })
            .catch(err => {
                detailModalContent.innerHTML = '<div style="color:red;padding:1rem;text-align:center">Gagal memuat rincian transaksi.</div>';
            });
    }

    function tutupDetailTrx() {
        modalDetailTrx.classList.remove('open');
    }
</script>
@endpush
