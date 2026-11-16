@extends('admin.layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi Kasir')
@section('page-subtitle', 'Laporan dan riwayat penjualan Angkringan Sekota')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem">
    <div>
        <a href="{{ route('admin.kasir.index') }}" class="btn-sm btn-outline-red" style="font-weight:700">
            ← Kembali ke Kasir (POS)
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stat-grid" style="grid-template-columns:repeat(4, 1fr);margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-label">Total Pendapatan (Omset)</div>
        <div class="stat-value red" style="font-size:1.35rem">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value">{{ $totalTransaksi }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pendapatan Tunai</div>
        <div class="stat-value" style="font-size:1.2rem;color:#059669">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pendapatan QRIS</div>
        <div class="stat-value" style="font-size:1.2rem;color:#2563EB">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
    </div>
</div>

{{-- FILTER FORM --}}
<div class="panel" style="margin-bottom:1.5rem">
    <form method="GET" action="{{ route('admin.kasir.riwayat') }}">
        <div style="display:grid;grid-template-columns:2fr 1.5fr 1.5fr 1fr auto;gap:.75rem;align-items:flex-end">
            <div>
                <label class="form-label" style="font-size:.75rem">Cari No. Trx / Kasir / Pelanggan</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Contoh: TRX-2026...">
            </div>
            <div>
                <label class="form-label" style="font-size:.75rem">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" style="font-size:.75rem">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-control">
            </div>
            <div>
                <label class="form-label" style="font-size:.75rem">Metode</label>
                <select name="metode" class="form-control">
                    <option value="">Semua</option>
                    <option value="tunai" {{ request('metode') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ request('metode') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div style="display:flex;gap:.5rem">
                <button type="submit" class="btn-sm btn-red" style="padding:.6rem 1rem">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.kasir.riwayat') }}" class="btn-sm btn-white" style="padding:.6rem .8rem" title="Reset filter">
                    ↺ Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- TABEL RIWAYAT --}}
<div class="panel">
    <div class="panel-title-row">
        <div class="panel-title">Daftar Transaksi</div>
        <span style="font-size:.8rem;color:var(--muted)">Menampilkan {{ $transaksiList->count() }} dari {{ $transaksiList->total() }} data</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Kasir</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Total Belanja</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiList as $t)
                <tr>
                    <td>
                        <strong style="font-family:monospace;color:var(--red)">{{ $t->nomor_transaksi }}</strong>
                    </td>
                    <td>{{ $t->formatted_tanggal_waktu }}</td>
                    <td>{{ $t->nama_kasir }}</td>
                    <td>{{ $t->nama_pelanggan ?: '-' }}</td>
                    <td>
                        @if($t->metode_pembayaran === 'tunai')
                            <span class="badge badge-yellow">💵 TUNAI</span>
                        @else
                            <span class="badge badge-green" style="background:#DBEAFE;color:#1E40AF">📱 QRIS</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color:var(--text);font-size:.9rem">{{ $t->formatted_total }}</strong>
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        <button type="button" class="btn-sm btn-white" onclick="lihatDetailTransaksi({{ $t->id }})" style="padding:.3rem .6rem;font-size:.75rem">
                            👁 Detail
                        </button>
                        <a href="{{ route('admin.kasir.struk', $t->id) }}" target="_blank" class="btn-sm btn-outline-red" style="padding:.3rem .6rem;font-size:.75rem">
                            🖨 Struk
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--muted)">
                        Tidak ada transaksi ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.25rem">
        {{ $transaksiList->links() }}
    </div>
</div>

{{-- MODAL DETAIL TRANSAKSI --}}
<div id="modalDetail" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;border-radius:14px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 25px -5px rgba(0,0,0,.1);padding:1.5rem">
        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:2px solid #FEE2E2;padding-bottom:.8rem;margin-bottom:1rem">
            <h3 style="font-size:1.1rem;color:var(--red)" id="modalDetailTitle">Detail Transaksi</h3>
            <button type="button" onclick="tutupModalDetail()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--muted)">&times;</button>
        </div>

        <div id="modalDetailContent" style="font-size:.85rem">
            <div style="text-align:center;padding:2rem;color:var(--muted)">Memuat data...</div>
        </div>

        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:.75rem;border-top:1px solid var(--border);padding-top:1rem">
            <button type="button" onclick="tutupModalDetail()" class="btn-sm btn-white">Tutup</button>
            <a id="modalDetailPrintBtn" href="#" target="_blank" class="btn-sm btn-red">🖨 Cetak Struk</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function lihatDetailTransaksi(id) {
        const modal = document.getElementById('modalDetail');
        const content = document.getElementById('modalDetailContent');
        const printBtn = document.getElementById('modalDetailPrintBtn');
        const title = document.getElementById('modalDetailTitle');

        modal.style.display = 'flex';
        content.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--muted)">Memuat data...</div>';
        printBtn.href = `/admin/kasir/struk/${id}`;

        fetch(`/admin/kasir/transaksi/${id}`)
            .then(res => res.json())
            .then(data => {
                title.textContent = `Detail: ${data.nomor_transaksi}`;
                let html = `
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;background:#F9FAFB;padding:.85rem;border-radius:8px;margin-bottom:1rem;border:1px solid var(--border)">
                        <div><strong style="color:var(--muted);font-size:.75rem">WAKTU</strong><div>${data.tanggal_waktu}</div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">KASIR</strong><div>${data.nama_kasir}</div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">METODE</strong><div><span class="badge ${data.metode_pembayaran === 'TUNAI' ? 'badge-yellow' : 'badge-green'}">${data.metode_pembayaran}</span></div></div>
                        <div><strong style="color:var(--muted);font-size:.75rem">PELANGGAN</strong><div>${data.nama_pelanggan || '-'}</div></div>
                    </div>

                    <strong style="display:block;margin-bottom:.5rem;color:var(--text)">Rincian Menu:</strong>
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

                    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:.85rem">
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

                content.innerHTML = html;
            })
            .catch(err => {
                content.innerHTML = '<div style="color:red;padding:1rem;text-align:center">Gagal memuat rincian transaksi.</div>';
            });
    }

    function tutupModalDetail() {
        document.getElementById('modalDetail').style.display = 'none';
    }
</script>
@endpush
@endsection
