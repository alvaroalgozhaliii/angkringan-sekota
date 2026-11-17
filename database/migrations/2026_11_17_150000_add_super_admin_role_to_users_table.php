<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('owner', 'pegawai', 'super_admin') NOT NULL DEFAULT 'pegawai'");
        }

        $targetAccountExists = DB::table('users')
            ->where('email', 'admin@angkringansekota.id')
            ->exists();

        if ($targetAccountExists) {
            DB::table('users')
                ->where('email', 'admin@angkringansekota.id')
                ->update(['role' => 'super_admin']);
        } else {
            DB::table('users')
                ->where('email', 'owner@sekota.id')
                ->update([
                    'email' => 'admin@angkringansekota.id',
                    'role' => 'super_admin',
                ]);
        }
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'owner']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('owner', 'pegawai') NOT NULL DEFAULT 'pegawai'");
        }
    }
};
