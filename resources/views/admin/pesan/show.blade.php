@extends('admin.layouts.app')
@section('page-title', 'Detail Pesan')
@section('title', 'Detail Pesan')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Detail Pesan</div>
        <div class="page-sub">Dari: {{ $pesan->nama_pengirim }}</div>
    </div>
    <a href="{{ route('admin.pesan.index') }}" class="btn-sm btn-white">← Kembali</a>
</div>

<div class="panel" style="max-width:650px">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
        <div>
            <div class="form-label" style="color:var(--muted)">Nama Pengirim</div>
            <div style="font-weight:600">{{ $pesan->nama_pengirim }}</div>
        </div>
        <div>
            <div class="form-label" style="color:var(--muted)">WhatsApp</div>
            <div>
                @if($pesan->nomor_wa)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pesan->nomor_wa) }}" target="_blank"
                       style="color:#059669;font-weight:600;text-decoration:none">
                        📱 {{ $pesan->nomor_wa }}
                    </a>
                @else
                    <span style="color:var(--muted)">-</span>
                @endif
            </div>
        </div>
        <div>
            <div class="form-label" style="color:var(--muted)">Email</div>
            <div>{{ $pesan->email ?? '-' }}</div>
        </div>
        <div>
            <div class="form-label" style="color:var(--muted)">Diterima</div>
            <div>{{ $pesan->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    <div style="background:#F9FAFB;border-radius:10px;padding:1.25rem;border:1px solid var(--border)">
        <div class="form-label" style="color:var(--muted);margin-bottom:.5rem">Isi Pesan</div>
        <p style="font-size:.9rem;line-height:1.75;white-space:pre-wrap">{{ $pesan->isi_pesan }}</p>
    </div>

    @if($pesan->nomor_wa)
    <div style="margin-top:1.25rem">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$pesan->nomor_wa) }}?text=Halo%20{{ urlencode($pesan->nama_pengirim) }},%20kami%20dari%20Angkringan%20Sekota%20ingin%20merespons%20pesan%20Anda."
           target="_blank" class="btn-sm btn-green btn-lg" style="text-decoration:none">
            💬 Balas via WhatsApp
        </a>
    </div>
    @endif
</div>
@endsection
