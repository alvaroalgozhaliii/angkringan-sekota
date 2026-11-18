<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiPesanan extends Model
{
    protected $table = 'notifikasi_pesanan';

    protected $fillable = [
        'transaksi_id', 'status', 'judul', 'pesan', 'channel', 'link_wa', 'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
