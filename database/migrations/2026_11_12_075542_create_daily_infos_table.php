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
        if (!Schema::hasTable('daily_infos')) {
            Schema::create('daily_infos', function (Blueprint $table) {
                $table->id();
                $table->string('opening_hours');
                $table->string('promo')->nullable();
                $table->text('announcement')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_infos');
    }
};
