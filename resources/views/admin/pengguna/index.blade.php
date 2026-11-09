@extends('admin.layouts.app')
@section('page-title', 'Pengguna Admin')
@section('title', 'Kelola Pengguna Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kelola Pengguna Admin</div>
        <div class="page-sub">Manajemen akun pengelola web profil dan panel admin Angkringan Sekota.</div>
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
                        <th>Aksi</th>
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
                            <span class="badge {{ $u->role === 'owner' ? 'badge-red' : 'badge-gray' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>
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
