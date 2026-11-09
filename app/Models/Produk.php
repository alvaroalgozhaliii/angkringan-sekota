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

    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar) {
            if (str_starts_with($this->gambar, 'http')) {
                return $this->gambar;
            }
            if (str_starts_with($this->gambar, 'images/')) {
                return asset($this->gambar);
            }
            return asset('storage/' . $this->gambar);
        }

        // Auto-match image by name in public/images/menu/
        $nameMap = [
            'mix platter'         => 'mix platter.png',
            'nasi pecel'          => 'pecel.png',
            'pecel'               => 'pecel.png',
            'tempe penyet'        => 'tempe penyet.png',
            'marsu'               => 'marsu.png',
            'es teh'              => 'es teh.png',
            'es kopi susu sekota' => 'es kopsu.png',
            'es kopi susu'        => 'es kopsu.png',
            'es kopsu'            => 'es kopsu.png',
            'opak'                => 'opak.png',
            'kerupuk opak'        => 'opak.png',
        ];

        $lower = strtolower(trim($this->nama));
        if (isset($nameMap[$lower]) && file_exists(public_path('images/menu/' . $nameMap[$lower]))) {
            return asset('images/menu/' . $nameMap[$lower]);
        }

        return asset('images/menu/mix platter.png');
    }
}
