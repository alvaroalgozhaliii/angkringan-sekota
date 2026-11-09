<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'nama', 'kategori', 'harga', 'deskripsi', 'gambar', 'status', 'urutan',
    ];

    protected $casts = [
        'harga' => 'decimal:0',
    ];

    public function scopeTayang($query)
    {
        return $query->where('status', 'tayang')->orderBy('urutan')->orderBy('id');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
