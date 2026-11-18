<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'kontak')) {
                $table->string('kontak', 50)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('kontak');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kontak', 'foto']);
        });
    }
};
