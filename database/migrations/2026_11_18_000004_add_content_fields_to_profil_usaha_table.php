<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_usaha', function (Blueprint $table) {
            foreach ([
                'judul_beranda' => fn (Blueprint $t) => $t->string('judul_beranda')->nullable()->after('teks_tentang_kami'),
                'deskripsi_beranda' => fn (Blueprint $t) => $t->text('deskripsi_beranda')->nullable()->after('judul_beranda'),
                'visi' => fn (Blueprint $t) => $t->text('visi')->nullable()->after('deskripsi_beranda'),
                'misi' => fn (Blueprint $t) => $t->text('misi')->nullable()->after('visi'),
                'maps_link' => fn (Blueprint $t) => $t->text('maps_link')->nullable()->after('alamat'),
                'warna_tema' => fn (Blueprint $t) => $t->string('warna_tema', 20)->default('#C8102E')->after('logo'),
                'tampilkan_banner' => fn (Blueprint $t) => $t->boolean('tampilkan_banner')->default(true)->after('warna_tema'),
            ] as $column => $definition) {
                if (! Schema::hasColumn('profil_usaha', $column)) {
                    $definition($table);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_usaha', function (Blueprint $table) {
            $table->dropColumn([
                'judul_beranda', 'deskripsi_beranda', 'visi', 'misi',
                'maps_link', 'warna_tema', 'tampilkan_banner',
            ]);
        });
    }
};
