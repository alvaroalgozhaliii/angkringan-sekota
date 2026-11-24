@extends('admin.layouts.app')
@section('page-title', 'Log Aktivitas')
@section('title', 'Log Aktivitas')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Log Aktivitas</div>
        <div class="page-sub">Catatan siapa melakukan apa, data sebelum/sesudah, dan waktu kejadian.</div>
    </div>
</div>

@php
    $roleOptions = [
        'super_admin' => 'Admin',
        'pegawai' => 'Pegawai',
        'sistem' => 'Sistem',
        'owner' => 'Owner',
    ];
    $bulanOptions = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $formatLogValue = fn ($value) => is_scalar($value) || $value === null ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
@endphp

<div class="panel log-filter-panel">
    <form method="GET" action="{{ route('pegawai.log.index') }}">
        <div class="log-filters">
            <div class="log-filter-search">
                <label class="form-label" for="q">Cari log</label>
                <input id="q" type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Aksi, modul, nama, atau data">
            </div>
            <div>
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="">Semua role</option>
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" for="modul">Modul</label>
                <select id="modul" name="modul" class="form-control">
                    <option value="">Semua modul</option>
                    @foreach($modul as $item)
                        <option value="{{ $item }}" {{ request('modul') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" for="bulan">Bulan</label>
                <select id="bulan" name="bulan" class="form-control">
                    <option value="">Semua bulan</option>
                    @foreach($bulanOptions as $value => $label)
                        <option value="{{ $value }}" {{ (string) request('bulan') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" for="tahun">Tahun</label>
                <select id="tahun" name="tahun" class="form-control">
                    <option value="">Semua tahun</option>
                    @foreach($tahun as $item)
                        <option value="{{ $item }}" {{ (string) request('tahun') === (string) $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="log-filter-actions">
                <button type="submit" class="btn-sm btn-red">Terapkan</button>
                @if(request()->hasAny(['q', 'role', 'modul', 'bulan', 'tahun']))
                    <a href="{{ route('pegawai.log.index') }}" class="btn-sm btn-white">Atur ulang</a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Oleh</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Data ringkas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="white-space:nowrap">{{ $log->created_at?->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            <span class="log-actor">{{ $log->user_nama }}</span>
                            <span class="log-role">({{ $log->roleLabel() }})</span>
                        </td>
                        <td>{{ $log->modul }}</td>
                        <td><strong>{{ $log->aksi }}</strong></td>
                        <td>
                            <div class="log-summary">{{ $log->ringkasanPerubahan() }}</div>
                            @if($log->data_sebelum || $log->data_sesudah)
                                <details class="log-detail">
                                    <summary>Detail</summary>
                                    @foreach(['Sebelum' => $log->data_sebelum, 'Sesudah' => $log->data_sesudah] as $label => $payload)
                                        @if($payload)
                                            <div class="log-box">
                                                <span class="log-box-label">{{ $label }}</span>
                                                @if(is_array($payload))
                                                    @foreach($payload as $key => $value)
                                                        <div class="log-box-row"><span class="log-box-value"><strong>{{ $key }}:</strong> {{ $formatLogValue($value) }}</span></div>
                                                    @endforeach
                                                @else
                                                    <div class="log-box-value">{{ $formatLogValue($payload) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </details>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted)">Belum ada aktivitas yang cocok dengan filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $logs->links() }}</div>
</div>
@endsection
