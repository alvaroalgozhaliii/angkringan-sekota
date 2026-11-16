<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'nomor_transaksi',
        'user_id',
        'nama_kasir',
        'nama_pelanggan',
        'catatan',
        'total_harga',
        'metode_pembayaran',
        'uang_diterima',
        'kembalian',
        'status',
    ];

    protected $casts = [
        'total_harga' => 'decimal:0',
        'uang_diterima' => 'decimal:0',
        'kembalian' => 'decimal:0',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedUangDiterimaAttribute(): string
    {
        return 'Rp ' . number_format($this->uang_diterima, 0, ',', '.');
    }

    public function getFormattedKembalianAttribute(): string
    {
        return 'Rp ' . number_format($this->kembalian, 0, ',', '.');
    }

    public function getFormattedWaktuAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('H:i') : '-';
    }

    public function getFormattedTanggalWaktuAttribute(): string
    {
        return $this->created_at ? $this->created_at->translatedFormat('d M Y, H:i') : '-';
    }

    /**
     * Generate unique sequential transaction number for today: TRX-YYYYMMDD-001
     */
    public static function generateNomorTransaksi(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $latest = self::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNum = (int) substr($latest->nomor_transaksi, strlen($prefix));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
