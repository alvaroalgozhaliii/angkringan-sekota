<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('produk', 'stok')) {
            DB::table('produk')->where('stok', 0)->update(['stok' => 50]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('produk', 'stok')) {
            DB::table('produk')->where('stok', 50)->update(['stok' => 0]);
        }
    }
};
