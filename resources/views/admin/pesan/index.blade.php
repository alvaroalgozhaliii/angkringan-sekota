@extends('admin.layouts.app')
@section('page-title', 'Pesan Masuk')
@section('title', 'Pesan Masuk')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Pesan Masuk</div>
        <div class="page-sub">Pesan dari pengunjung web profil Angkringan Sekota.</div>
    </div>
    @if($belumDibaca > 0)
    <form method="POST" action="{{ route('admin.pesan.markAllRead') }}">
        @csrf
        <button type="submit" class="btn-sm btn-white">✓ Tandai Semua Dibaca ({{ $belumDibaca }})</button>
    </form>
    @endif
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Pengirim</th>
                    <th>WhatsApp</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesan as $p)
                <tr style="{{ $p->status === 'belum_dibaca' ? 'font-weight:600;background:#FFFBEB' : '' }}">
                    <td>
                        @if($p->status === 'belum_dibaca')
                            <span style="display:inline-block;width:7px;height:7px;background:#EF4444;border-radius:50%;margin-right:.4rem;vertical-align:middle"></span>
                        @endif
                        {{ $p->nama_pengirim }}
                    </td>
                    <td>{{ $p->nomor_wa ?? '-' }}</td>
                    <td style="max-width:300px">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:400">
                            {{ Str::limit($p->isi_pesan, 60) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $p->status === 'belum_dibaca' ? 'badge-yellow' : 'badge-green' }}">
                            {{ $p->status === 'belum_dibaca' ? 'Belum dibaca' : 'Sudah dibaca' }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:.8rem">{{ $p->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:.75rem">
                            <a href="{{ route('admin.pesan.show', $p) }}" class="action-link">Baca</a>
                            <form method="POST" action="{{ route('admin.pesan.destroy', $p) }}"
                                  onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-link del" style="background:none;border:none;cursor:pointer;padding:0">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:3rem">Belum ada pesan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem">{{ $pesan->links() }}</div>
</div>
@endsection
