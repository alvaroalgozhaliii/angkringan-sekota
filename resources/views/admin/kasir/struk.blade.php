<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk #{{ $transaksi->nomor_transaksi }} — {{ $profil->nama_usaha }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.35;
            color: #000;
            background: #f0f0f0;
            padding: 20px 10px;
        }
        .struk-card {
            background: #fff;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            padding: 16px 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
            border-radius: 6px;
        }
        .struk-header {
            text-align: center;
            margin-bottom: 8px;
        }
        .struk-logo {
            max-width: 55px;
            height: auto;
            margin-bottom: 4px;
        }
        .struk-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .struk-sub {
            font-size: 10.5px;
            color: #333;
            margin-top: 2px;
        }
        .divider {
            border-top: 1px dashed #444;
            margin: 8px 0;
        }
        .divider-double {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .meta-label {
            color: #333;
        }
        .meta-value {
            font-weight: 600;
            text-align: right;
        }
        .items-table {
            width: 100%;
            margin: 6px 0;
            font-size: 11px;
        }
        .items-table tr td {
            padding: 3px 0;
            vertical-align: top;
        }
        .item-name {
            font-weight: 600;
        }
        .item-calc {
            font-size: 10px;
            color: #444;
        }
        .item-subtotal {
            text-align: right;
            font-weight: 600;
            white-space: nowrap;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 800;
            margin-top: 4px;
        }
        .struk-footer {
            text-align: center;
            margin-top: 12px;
            font-size: 10.5px;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            margin: 4px;
        }
        .btn-print {
            background: #C8102E;
            color: #fff;
        }
        .btn-back {
            background: #E5E7EB;
            color: #1F2937;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }
            .struk-card {
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
                padding: 4px 6px;
                width: 100%;
            }
            .actions {
                display: none !important;
            }
            @page {
                margin: 0;
                size: 58mm auto;
            }
        }
    </style>
</head>
<body>

<div class="struk-card">
    <div class="struk-header">
        @if($profil->logo)
            <img src="{{ $profil->logo_url }}" alt="Logo" class="struk-logo">
        @endif
        <div class="struk-title">{{ $profil->nama_usaha }}</div>
        <div class="struk-sub">{{ $profil->alamat ?: 'Tulungagung, Jawa Timur' }}</div>
        @if($profil->whatsapp)
            <div class="struk-sub">WA: {{ $profil->whatsapp }}</div>
        @endif
    </div>

    <div class="divider"></div>

    <div class="meta-row">
        <span class="meta-label">No. Trx:</span>
        <span class="meta-value">{{ $transaksi->nomor_transaksi }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Waktu:</span>
        <span class="meta-value">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Kasir:</span>
        <span class="meta-value">{{ $transaksi->nama_kasir }}</span>
    </div>
    @if($transaksi->nama_pelanggan)
    <div class="meta-row">
        <span class="meta-label">Pelanggan:</span>
        <span class="meta-value">{{ $transaksi->nama_pelanggan }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <table class="items-table">
        @foreach($transaksi->details as $item)
        <tr>
            <td colspan="2">
                <div class="item-name">{{ $item->nama_produk }}</div>
                <div class="item-calc">{{ $item->jumlah }} x {{ $item->harga_format }}</div>
            </td>
            <td class="item-subtotal">
                {{ $item->subtotal_format }}
            </td>
        </tr>
        @endforeach
    </table>

    <div class="divider-double"></div>

    <div class="total-row">
        <span>TOTAL</span>
        <span>{{ $transaksi->formatted_total }}</span>
    </div>

    <div class="divider"></div>

    <div class="meta-row">
        <span class="meta-label">Metode:</span>
        <span class="meta-value">{{ strtoupper($transaksi->metode_pembayaran) }}</span>
    </div>

    @if($transaksi->metode_pembayaran === 'tunai')
    <div class="meta-row">
        <span class="meta-label">Tunai:</span>
        <span class="meta-value">{{ $transaksi->formatted_uang_diterima }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Kembalian:</span>
        <span class="meta-value">{{ $transaksi->formatted_kembalian }}</span>
    </div>
    @else
    <div class="meta-row">
        <span class="meta-label">Status QRIS:</span>
        <span class="meta-value">LUNAS / BERHASIL</span>
    </div>
    @endif

    @if($transaksi->catatan)
    <div class="divider"></div>
    <div class="meta-row" style="font-size:10px">
        <span class="meta-label">Catatan:</span>
        <span class="meta-value">{{ $transaksi->catatan }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <div class="struk-footer">
        <div style="font-weight:700">MATUR NUWUN! 🙏</div>
        <div style="font-size:10px;margin-top:2px">Semoga berkah & nikmat.</div>
        <div style="font-size:9.5px;color:#666;margin-top:4px">Kritik & Saran WA: {{ $profil->whatsapp }}</div>
    </div>
</div>

<div class="actions">
    <button class="btn btn-print" onclick="window.print()">🖨 Cetak Struk</button>
    <a href="{{ route('admin.kasir.index') }}" class="btn btn-back">⬅ Kembali ke Kasir</a>
</div>

<script>
    // Auto-print saat parameter print=1 diberikan
    if (window.location.search.includes('autoprint=1')) {
        window.addEventListener('load', () => {
            window.print();
        });
    }
</script>
</body>
</html>
