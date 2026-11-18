<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'kontak',
        'foto',
        'password',
        'role',
    ];

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'owner', 'pegawai'], true);
    }

    public function isPegawaiPanel(): bool
    {
        return in_array($this->role, ['pegawai', 'super_admin'], true);
    }

    public function fotoUrl(): string
    {
        if (! $this->foto) {
            return asset('images/logo.png');
        }
        if (str_starts_with($this->foto, 'http') || str_starts_with($this->foto, 'images/')) {
            return str_starts_with($this->foto, 'http') ? $this->foto : asset($this->foto);
        }

        return asset('storage/'.$this->foto);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'owner' => 'Admin',
            default => 'Pegawai',
        };
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
