<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profil_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha')->default('Angkringan Sekota Tulungagung');
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('alamat')->nullable();
            $table->text('teks_tentang_kami')->nullable();
            $table->string('logo')->nullable();
            $table->string('jam_buka')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_usaha');
    }
};
