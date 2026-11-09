<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';
    protected $fillable = [
        'nama_pengirim', 'nomor_wa', 'email', 'isi_pesan', 'status',
    ];

    public function scopeBelumDibaca($query)
    {
        return $query->where('status', 'belum_dibaca');
    }

    public function markAsRead(): void
    {
        $this->update(['status' => 'sudah_dibaca']);
    }
}
