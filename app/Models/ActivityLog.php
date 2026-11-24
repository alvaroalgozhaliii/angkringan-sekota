<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_nama', 'role', 'modul', 'aksi', 'entitas', 'entitas_id',
        'data_sebelum', 'data_sesudah', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roleLabel(): string
    {
        $role = $this->role ?: $this->user?->role;

        return match ($role) {
            'super_admin' => 'Admin',
            'owner' => 'Owner',
            'pegawai' => 'Pegawai',
            default => 'Sistem',
        };
    }

    public function ringkasanPerubahan(int $maksItem = 2, int $maksKarakter = 120): string
    {
        $sebelum = is_array($this->data_sebelum) ? $this->data_sebelum : [];
        $sesudah = is_array($this->data_sesudah) ? $this->data_sesudah : [];
        $kunci = array_values(array_unique(array_merge(array_keys($sebelum), array_keys($sesudah))));

        if ($kunci === []) {
            return 'Tidak ada data';
        }

        $bagian = [];
        foreach ($kunci as $kunciData) {
            $dari = $this->formatRingkas($sebelum[$kunciData] ?? null);
            $ke = $this->formatRingkas($sesudah[$kunciData] ?? null);

            if ($dari !== null && $ke !== null && $dari !== $ke) {
                $bagian[] = $kunciData.': '.$dari.' menjadi '.$ke;
            } elseif ($ke !== null) {
                $bagian[] = $kunciData.': '.$ke;
            } elseif ($dari !== null) {
                $bagian[] = $kunciData.': '.$dari.' dihapus';
            }
        }

        if ($bagian === []) {
            return 'Tidak ada data';
        }

        $sisa = count($bagian) - $maksItem;
        $teks = implode(', ', array_slice($bagian, 0, $maksItem));
        $panjang = max(20, $maksKarakter - ($sisa > 0 ? strlen(', +'.$sisa) : 0));
        $teks = Str::limit($teks, $panjang, '');

        if ($sisa > 0) {
            $teks .= ', +'.$sisa;
        }

        return rtrim($teks, ', ');
    }

    private function formatRingkas(mixed $nilai): ?string
    {
        if ($nilai === null) {
            return null;
        }

        if (is_array($nilai)) {
            $nilai = json_encode($nilai, JSON_UNESCAPED_UNICODE);
        } elseif (is_bool($nilai)) {
            $nilai = $nilai ? 'ya' : 'tidak';
        }

        $teks = trim((string) preg_replace('/\s+/', ' ', (string) $nilai));

        return $teks === '' ? null : Str::limit($teks, 40);
    }
}
