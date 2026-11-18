<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTransaksi extends Model
{
    protected $table = 'pengaturan_transaksi';

    protected $fillable = [
        'tunai_aktif',
        'qris_aktif',
        'qris_gambar',
        'qris_nama_merchant',
        'tunai_syarat',
        'tunai_instruksi',
        'pengiriman_aktif',
        'ambil_di_toko_aktif',
        'area_pengiriman',
        'tarif_pengiriman',
        'alamat_toko',
        'batas_bayar_menit',
    ];

    protected $casts = [
        'tunai_aktif' => 'boolean',
        'qris_aktif' => 'boolean',
        'pengiriman_aktif' => 'boolean',
        'ambil_di_toko_aktif' => 'boolean',
        'tarif_pengiriman' => 'decimal:2',
        'batas_bayar_menit' => 'integer',
    ];

    public function qrisUrl(): ?string
    {
        return $this->qris_gambar
            ? (str_starts_with($this->qris_gambar, 'http')
                ? $this->qris_gambar
                : asset('storage/'.$this->qris_gambar))
            : null;
    }

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
