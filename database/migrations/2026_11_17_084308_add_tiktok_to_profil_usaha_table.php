<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('profil_usaha', 'tiktok')) {
            Schema::table('profil_usaha', function (Blueprint $table) {
                $table->string('tiktok')->nullable()->after('facebook');
            });
        }
    }

    public function down(): void
    {
        Schema::table('profil_usaha', function (Blueprint $table) {
            $table->dropColumn('tiktok');
        });
    }
};
