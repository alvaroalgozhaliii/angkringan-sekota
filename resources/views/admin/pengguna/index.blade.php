@extends('admin.layouts.app')
@section('page-title', 'Manajemen Akun & Role')
@section('title', 'Manajemen Akun & Role')

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
        <div class="table-wrap">
            <table>
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
                        <td>
                            <strong>{{ $u->name }}</strong>
                            @if($u->id === auth()->id())
                                <span class="badge badge-green" style="font-size:.65rem;margin-left:.25rem">Anda</span>
                            @endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->isSuperAdmin() ? 'badge-red' : ($u->isOwner() ? 'badge-green' : 'badge-gray') }}">
                                {{ $u->roleLabel() }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.pengguna.role', $u) }}" style="display:flex;gap:.4rem;align-items:center;margin-bottom:.45rem">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-control" aria-label="Role {{ $u->name }}" style="min-width:125px;padding:.35rem">
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
