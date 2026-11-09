<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilUsaha extends Model
{
    protected $table = 'profil_usaha';
    protected $fillable = [
        'nama_usaha', 'whatsapp', 'instagram', 'facebook',
        'alamat', 'teks_tentang_kami', 'logo', 'jam_buka', 'email',
    ];

    /**
     * Get singleton profile (always use record ID 1)
     */
    public static function getSingleton(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'nama_usaha'       => 'Angkringan Sekota Tulungagung',
            'whatsapp'         => '0812-3456-7890',
            'instagram'        => '@angkringan.sekota',
            'facebook'         => 'Angkringan Sekota',
            'alamat'           => 'Tulungagung, Jawa Timur',
            'teks_tentang_kami'=> 'Angkringan Sekota hadir dari kecintaan kami pada suasana sederhana, rasa yang jujur, dan obrolan yang tak ada habisnya.',
            'jam_buka'         => '17.00 - 23.00 WIB',
        ]);
    }
}
