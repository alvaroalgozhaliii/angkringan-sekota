<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTransaksi extends Model
{
    protected $table = 'pengaturan_transaksi';

    protected $fillable = [
        'tunai_aktif',
        'qris_aktif',
    ];

    protected $casts = [
        'tunai_aktif' => 'boolean',
        'qris_aktif' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'tunai_aktif' => true,
            'qris_aktif' => true,
        ]);
    }

    public function metodeDiizinkan(): array
    {
        $metode = [];
        if ($this->tunai_aktif) {
            $metode[] = 'tunai';
        }
        if ($this->qris_aktif) {
            $metode[] = 'qris';
        }

        return $metode;
    }
}
