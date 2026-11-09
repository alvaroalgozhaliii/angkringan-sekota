<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin users
        DB::table('users')->insert([
            [
                'name'       => 'Owner Sekota',
                'email'      => 'owner@sekota.id',
                'password'   => Hash::make('sekota123'),
                'role'       => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Pegawai Sekota',
                'email'      => 'pegawai@sekota.id',
                'password'   => Hash::make('pegawai123'),
                'role'       => 'pegawai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Profil usaha
        DB::table('profil_usaha')->insert([
            'nama_usaha'        => 'Angkringan Sekota Tulungagung',
            'whatsapp'          => '0812-3456-7890',
            'instagram'         => '@angkringan.sekota',
            'facebook'          => 'Angkringan Sekota',
            'alamat'            => 'Jl. Raya Tulungagung, Jawa Timur',
            'teks_tentang_kami' => 'Angkringan Sekota hadir dari kecintaan kami pada suasana sederhana, rasa yang jujur, dan obrolan yang tak ada habisnya. Kami menyajikan menu khas angkringan dengan cita rasa autentik yang terjangkau.',
            'jam_buka'          => '17.00 - 23.00 WIB',
            'email'             => 'angkringan.sekota@gmail.com',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Produk
        $produk = [
            ['nama' => 'Mix Platter',        'kategori' => 'Makanan', 'harga' => 12000, 'status' => 'tayang', 'urutan' => 1],
            ['nama' => 'Nasi Pecel',         'kategori' => 'Makanan', 'harga' => 8000,  'status' => 'tayang', 'urutan' => 2],
            ['nama' => 'Tempe Penyet',       'kategori' => 'Makanan', 'harga' => 12000, 'status' => 'tayang', 'urutan' => 3],
            ['nama' => 'MARSU',              'kategori' => 'Minuman', 'harga' => 6000,  'status' => 'tayang', 'urutan' => 4],
            ['nama' => 'Es Teh',             'kategori' => 'Minuman', 'harga' => 4000,  'status' => 'tayang', 'urutan' => 5],
            ['nama' => 'Es Kopi Susu Sekota','kategori' => 'Minuman', 'harga' => 12000, 'status' => 'tayang', 'urutan' => 6],
        ];
        foreach ($produk as $p) {
            DB::table('produk')->insert(array_merge($p, [
                'deskripsi'  => null,
                'gambar'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Sample pesan
        DB::table('pesan')->insert([
            [
                'nama_pengirim' => 'Budi Santoso',
                'nomor_wa'      => '0812-1111-2222',
                'email'         => null,
                'isi_pesan'     => 'Halo, apakah bisa pesan untuk acara arisan 20 orang?',
                'status'        => 'belum_dibaca',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_pengirim' => 'Siti Rahayu',
                'nomor_wa'      => '0813-9999-8888',
                'email'         => 'siti@email.com',
                'isi_pesan'     => 'Menu Es Kopi Susu Sekota itu enak banget! Kapan buka lagi?',
                'status'        => 'belum_dibaca',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
