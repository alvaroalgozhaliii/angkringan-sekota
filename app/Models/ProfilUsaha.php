<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilUsaha extends Model
{
    protected $table = 'profil_usaha';

    protected $fillable = [
        'nama_usaha', 'whatsapp', 'instagram', 'facebook', 'tiktok',
        'alamat', 'maps_link', 'teks_tentang_kami', 'logo', 'jam_buka', 'email',
        'judul_beranda', 'deskripsi_beranda', 'visi', 'misi',
        'warna_tema', 'tampilkan_banner',
    ];

    protected $casts = [
        'tampilkan_banner' => 'boolean',
    ];

    public function warnaTema(): string
    {
        $warna = $this->warna_tema ?? '#C8102E';

        return preg_match('/^#[0-9a-fA-F]{6}$/', $warna) ? $warna : '#C8102E';
    }

    /**
     * Get logo URL helper
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            if (str_starts_with($this->logo, 'http')) {
                return $this->logo;
            }
            if (str_starts_with($this->logo, 'images/')) {
                return asset($this->logo);
            }

            return asset('storage/'.$this->logo);
        }

        return asset('images/logo.png');
    }

    /**
     * Get singleton profile (always use record ID 1)
     */
    public static function getSingleton(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'nama_usaha' => 'Angkringan Sekota Tulungagung',
            'whatsapp' => '0812-3456-7890',
            'instagram' => '@angkringan.sekota',
            'facebook' => 'Angkringan Sekota',
            'tiktok' => '@angkringan.sekota',
            'alamat' => 'Tulungagung, Jawa Timur',
            'teks_tentang_kami' => 'Angkringan Sekota hadir dari kecintaan kami pada suasana sederhana, rasa yang jujur, dan obrolan yang tak ada habisnya.',
            'jam_buka' => '17.00 - 23.00 WIB',
        ]);
    }
}
