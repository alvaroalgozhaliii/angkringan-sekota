<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('role', 32)->nullable()->after('user_nama');
        });

        DB::table('activity_logs')
            ->whereNull('role')
            ->whereNotNull('user_id')
            ->chunkById(200, function ($logs) {
                $userIds = $logs->pluck('user_id')->filter()->unique()->all();
                $roles = DB::table('users')->whereIn('id', $userIds)->pluck('role', 'id');

                foreach ($logs as $log) {
                    DB::table('activity_logs')->where('id', $log->id)->update([
                        'role' => $roles[$log->user_id] ?? 'sistem',
                    ]);
                }
            });

        DB::table('activity_logs')->whereNull('role')->update(['role' => 'sistem']);
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
