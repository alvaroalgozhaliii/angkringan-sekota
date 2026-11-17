@extends('admin.layouts.app')
@section('page-title', 'Ulasan Pengunjung')
@section('page-icon', '⭐')
@section('title', 'Ulasan Pengunjung')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Ulasan Pengunjung</div>
        <div class="page-sub">Kelola ulasan dan penilaian dari pengunjung web profil Angkringan Sekota.</div>
    </div>
</div>

{{-- Flash Message --}}
@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;color:#065F46;padding:.85rem 1.1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.88rem;font-weight:600;display:flex;align-items:center;gap:.6rem">
    <span>✓</span> {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Ulasan</div>
        <div class="stat-value">{{ $totalAll }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ditampilkan</div>
        <div class="stat-value" style="color:#059669">{{ $totalAktif }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Disembunyikan</div>
        <div class="stat-value" style="color:#D97706">{{ $totalPending }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Rata-rata Rating</div>
        <div class="stat-value" style="color:#F59E0B">★ {{ $avgRating }}</div>
    </div>
</div>

{{-- Filter Tabs --}}
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap">
    <a href="{{ route('admin.ulasan.index', ['filter' => 'semua']) }}"
       style="padding:.45rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;border:1.5px solid {{ $filter === 'semua' ? 'var(--red)' : 'var(--border)' }};background:{{ $filter === 'semua' ? 'var(--red)' : '#fff' }};color:{{ $filter === 'semua' ? '#fff' : 'var(--text)' }}">
        Semua ({{ $totalAll }})
    </a>
    <a href="{{ route('admin.ulasan.index', ['filter' => 'aktif']) }}"
       style="padding:.45rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;border:1.5px solid {{ $filter === 'aktif' ? '#059669' : 'var(--border)' }};background:{{ $filter === 'aktif' ? '#059669' : '#fff' }};color:{{ $filter === 'aktif' ? '#fff' : 'var(--text)' }}">
        Ditampilkan ({{ $totalAktif }})
    </a>
    <a href="{{ route('admin.ulasan.index', ['filter' => 'pending']) }}"
       style="padding:.45rem 1.1rem;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;border:1.5px solid {{ $filter === 'pending' ? '#D97706' : 'var(--border)' }};background:{{ $filter === 'pending' ? '#D97706' : '#fff' }};color:{{ $filter === 'pending' ? '#fff' : 'var(--text)' }}">
        Disembunyikan ({{ $totalPending }})
    </a>
</div>

{{-- Tabel Ulasan --}}
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Pengunjung</th>
                    <th>Rating</th>
                    <th style="min-width:280px">Isi Ulasan</th>
                    <th>Sumber</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ulasan as $u)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.6rem">
                            <div style="width:32px;height:32px;border-radius:50%;background:#C8102E;color:#fff;font-weight:700;font-size:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                {{ $u->inisial }}
                            </div>
                            <span style="font-weight:600;font-size:.85rem">{{ $u->customer_name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="color:#F59E0B;font-weight:700;font-size:.9rem">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $u->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                        <div style="font-size:.7rem;color:var(--muted)">{{ $u->rating }}/5</div>
                    </td>
                    <td>
                        <p style="font-size:.82rem;color:var(--text);line-height:1.5;max-width:300px">
                            &ldquo;{{ Str::limit($u->review, 120) }}&rdquo;
                        </p>
                    </td>
                    <td>
                        <span style="font-size:.78rem;background:#F3F4F6;color:var(--muted);padding:2px 8px;border-radius:6px;font-weight:500">
                            {{ $u->source ?? 'Web' }}
                        </span>
                    </td>
                    <td>
                        @if($u->is_active)
                            <span class="badge badge-green">Ditampilkan</span>
                        @else
                            <span class="badge badge-yellow">Disembunyikan</span>
                        @endif
                    </td>
                    <td style="color:var(--muted);font-size:.78rem;white-space:nowrap">
                        {{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.6rem;align-items:center">
                            {{-- Toggle Tampil/Sembunyikan --}}
                            <form method="POST" action="{{ route('admin.ulasan.toggle', $u) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-link"
                                        style="background:none;border:none;cursor:pointer;padding:0;font-size:.82rem;white-space:nowrap"
                                        title="{{ $u->is_active ? 'Sembunyikan ulasan ini' : 'Tampilkan ulasan ini' }}">
                                    {{ $u->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                </button>
                            </form>
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.ulasan.destroy', $u) }}"
                                  onsubmit="return confirm('Hapus ulasan dari {{ addslashes($u->customer_name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-link del"
                                        style="background:none;border:none;cursor:pointer;padding:0;font-size:.82rem">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--muted);padding:3rem;font-size:.88rem">
                        Belum ada ulasan masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $ulasan->links() }}</div>
</div>
@endsection
