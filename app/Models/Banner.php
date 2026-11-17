<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'link',
        'urutan',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan'   => 'integer',
    ];

    /**
     * Scope untuk banner yang aktif urut dari urutan terkecil lalu terbaru
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', 1)->orderBy('urutan', 'asc')->orderBy('id', 'desc');
    }

    /**
     * Accessor URL gambar
     */
    public function getGambarUrlAttribute(): string
    {
        if (!$this->gambar) {
            return asset('images/hero/slide1.jpg');
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        if (str_starts_with($this->gambar, 'images/')) {
            return asset($this->gambar);
        }

        return asset('storage/' . $this->gambar);
    }
}
