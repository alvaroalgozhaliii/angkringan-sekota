@extends('admin.layouts.app')
@section('page-title', 'Manajemen Akun & Role')
@section('title', 'Manajemen Akun & Role')

@push('styles')
<style>
    .account-table-wrap{overflow-x:auto}
    .account-management-table{min-width:620px}
    .account-action-list{display:flex;align-items:center;gap:.65rem;flex-wrap:wrap}
    .account-role-form{display:flex;align-items:center;gap:.4rem}
    .account-role-form select{min-width:125px;padding:.35rem}
    @media(max-width:900px){
        .account-panel{min-width:0}
        .account-table-wrap{overflow:visible}
        .account-management-table{display:block;min-width:0;width:100%}
        .account-management-table thead{display:none}
        .account-management-table tbody{display:grid;gap:.75rem}
        .account-management-table tbody tr{display:block;padding:.5rem .8rem;background:#fff;border:1px solid var(--border);border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .account-management-table tbody tr:hover{background:#fff}
        .account-management-table tbody td{display:grid;grid-template-columns:4.5rem minmax(0,1fr);gap:.5rem;align-items:center;padding:.6rem .1rem;border-bottom:1px solid var(--border);overflow-wrap:anywhere}
        .account-management-table tbody tr td:last-child{border-bottom:0}
        .account-management-table tbody td::before{content:attr(data-label);font-size:.68rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)}
        .account-management-table tbody td[data-label="Kelola"]{display:block}
        .account-management-table tbody td[data-label="Kelola"]::before{display:block;margin-bottom:.5rem}
        .account-action-list{align-items:stretch;gap:.6rem}
        .account-role-form{width:100%}
        .account-role-form select{flex:1;min-width:0 !important}
        .account-role-form button,.account-action-list > form button{min-height:38px;padding:.35rem .6rem !important}
        .account-management-table tbody td[colspan]{display:block;text-align:center}
        .account-management-table tbody td[colspan]::before{content:none}
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Manajemen Akun &amp; Role</div>
        <div class="page-sub">Kelola akun admin dan hak akses pengguna Angkringan Sekota.</div>
    </div>
</div>

<div class="two-col">
    <!-- Form Tambah Pengguna -->
    <div class="panel">
        <div class="panel-title">Tambah Pengguna Baru</div>
        <form method="POST" action="{{ route('admin.pengguna.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="cth. Staff Sekota" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="cth. staff@sekota.id" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Peran (Role)</label>
                <select name="role" class="form-control" required>
                    <option value="pegawai" {{ old('role') === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                    <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
            <div class="btn-row">
                <button type="submit" class="btn-sm btn-red">Simpan Pengguna</button>
            </div>
        </form>
    </div>

    <!-- Daftar Pengguna -->
    <div class="panel">
        <div class="panel-title">Daftar Pengguna Aktif</div>
        <div class="table-wrap account-table-wrap">
            <table class="account-management-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengguna as $u)
                    <tr>
                        <td data-label="Nama">
                            <strong>{{ $u->name }}</strong>
                            @if($u->id === auth()->id())
                                <span class="badge badge-green" style="font-size:.65rem;margin-left:.25rem">Anda</span>
                            @endif
                        </td>
                        <td data-label="Email">{{ $u->email }}</td>
                        <td data-label="Role">
                            <span class="badge {{ $u->isSuperAdmin() ? 'badge-red' : ($u->isOwner() ? 'badge-green' : 'badge-gray') }}">
                                {{ $u->roleLabel() }}
                            </span>
                        </td>
                        <td data-label="Kelola">
                            <div class="account-action-list">
                            <form class="account-role-form" method="POST" action="{{ route('admin.pengguna.role', $u) }}">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-control" aria-label="Role {{ $u->name }}">
                                    <option value="pegawai" {{ $u->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                    <option value="owner" {{ $u->role === 'owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                <button type="submit" class="action-link" style="background:none;border:none;cursor:pointer;padding:0">Simpan</button>
                            </form>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-link del" style="background:none;border:none;cursor:pointer;padding:0">Hapus</button>
                            </form>
                            @else
                                <span style="color:var(--muted);font-size:.78rem">-</span>
                            @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--muted);padding:2rem">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
