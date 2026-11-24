<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class ActivityLogger
{
    public static function log(
        string $modul,
        string $aksi,
        mixed $sebelum = null,
        mixed $sesudah = null,
        ?string $entitas = null,
        ?int $entitasId = null,
        ?Request $request = null,
        ?string $actorNama = null,
    ): ActivityLog {
        $request ??= request();
        $user = Auth::user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_nama' => $actorNama ?? $user?->name ?? 'Sistem',
            'role' => $user?->role ?? 'sistem',
            'modul' => $modul,
            'aksi' => $aksi,
            'entitas' => $entitas,
            'entitas_id' => $entitasId,
            'data_sebelum' => self::ringkas($sebelum),
            'data_sesudah' => self::ringkas($sesudah),
            'ip_address' => $request?->ip(),
            'created_at' => now(),
        ]);
    }

    private static function ringkas(mixed $data): ?array
    {
        if ($data === null) {
            return null;
        }

        if (! is_array($data)) {
            return ['nilai' => self::pendek((string) $data)];
        }

        $hasil = [];
        foreach ($data as $kunci => $nilai) {
            if (is_array($nilai)) {
                $nilai = json_encode($nilai, JSON_UNESCAPED_UNICODE);
            } elseif (is_bool($nilai)) {
                $nilai = $nilai ? 'ya' : 'tidak';
            } elseif ($nilai === null) {
                $nilai = '-';
            }
            $hasil[(string) $kunci] = self::pendek((string) $nilai);
        }

        return $hasil;
    }

    private static function pendek(string $nilai): string
    {
        return Str::limit(str_replace(["\r", "\n"], ' ', $nilai), 250);
    }
}
