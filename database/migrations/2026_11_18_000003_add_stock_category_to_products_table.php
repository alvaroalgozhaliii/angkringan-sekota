<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        Schema::table('produk', function (Blueprint $table) {
            if (! Schema::hasColumn('produk', 'kategori_id')) {
                $table->foreignId('kategori_id')->nullable()->after('kategori')
                    ->constrained('kategori_produk')->nullOnDelete();
            }
            if (! Schema::hasColumn('produk', 'stok')) {
                $table->unsignedInteger('stok')->default(0)->after('harga');
            }
            if (! Schema::hasColumn('produk', 'is_best_seller')) {
                $table->boolean('is_best_seller')->default(false)->after('stok');
            }
        });

        $now = now();
        foreach (DB::table('produk')->distinct()->pluck('kategori')->filter() as $nama) {
            $slug = Str::slug($nama) ?: 'kategori';
            $existing = DB::table('kategori_produk')->where('nama', $nama)->first();
            if (! $existing) {
                $suffix = 1;
                while (DB::table('kategori_produk')->where('slug', $slug)->exists()) {
                    $slug = Str::slug($nama).'-'.$suffix++;
                }
                DB::table('kategori_produk')->insert([
                    'nama' => $nama,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $kategori = DB::table('kategori_produk')->pluck('id', 'nama');
        foreach ($kategori as $nama => $id) {
            DB::table('produk')->where('kategori', $nama)->update(['kategori_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['kategori_id', 'stok', 'is_best_seller']);
        });
        Schema::dropIfExists('kategori_produk');
    }
};
