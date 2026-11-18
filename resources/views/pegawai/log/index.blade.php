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
                        <td>{{ $log->user_nama }}</td>
                        <td>{{ $log->modul }}</td>
                        <td><strong>{{ $log->aksi }}</strong></td>
                        <td>
                            @if($log->data_sebelum)
                                <div class="hint">Sebelum: {{ json_encode($log->data_sebelum, JSON_UNESCAPED_UNICODE) }}</div>
                            @endif
                            @if($log->data_sesudah)
                                <div class="hint">Sesudah: {{ json_encode($log->data_sesudah, JSON_UNESCAPED_UNICODE) }}</div>
                            @endif
                            @if(! $log->data_sebelum && ! $log->data_sesudah)<span class="hint">-</span>@endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted)">Belum ada aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $logs->links() }}</div>
</div>
@endsection
