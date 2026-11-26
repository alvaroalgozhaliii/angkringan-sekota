@extends('admin.layouts.app')
@section('page-title', 'Riwayat pelanggan')
@section('title', 'Riwayat Pelanggan')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">{{ $nama }}</h2>
        <div class="page-sub">Riwayat pesanan yang tercatat dengan nama ini.</div>
    </div>
    <a href="{{ route('admin.pelanggan.index') }}" class="btn-sm btn-white">Kembali ke daftar</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Waktu</th>
                    <th>Metode</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan as $item)
                    <tr>
                        <td><strong>{{ $item->nomor_transaksi }}</strong></td>
                        <td style="white-space:nowrap">{{ $item->formatted_tanggal_waktu }}</td>
                        <td>{{ strtoupper($item->metode_pembayaran) }}</td>
                        <td>{{ $item->formatted_total }}</td>
                        <td><span class="badge badge-{{ \App\Support\OrderStatus::badge($item->status) }}">{{ $item->status_label }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
