<?php

namespace App\Models;

use App\Support\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'nomor_transaksi',
        'user_id',
        'nama_kasir',
        'sumber',
        'nama_pelanggan',
        'catatan',
        'total_harga',
        'metode_pembayaran',
        'uang_diterima',
        'kembalian',
        'status',
        'no_telepon_pelanggan',
        'email_pelanggan',
        'alamat_pengiriman',
        'metode_pengambilan',
        'bukti_pembayaran',
        'alasan_penolakan',
        'batas_bayar_at',
        'waktu_dibayar_at',
        'stok_dikurangi',
        'verified_by',
        'verified_at',
        'disetujui_oleh',
        'disetujui_at',
        'status_updated_at',
    ];

    protected $casts = [
        'total_harga' => 'decimal:0',
        'uang_diterima' => 'decimal:0',
        'kembalian' => 'decimal:0',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'batas_bayar_at' => 'datetime',
        'waktu_dibayar_at' => 'datetime',
        'verified_at' => 'datetime',
        'disetujui_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'stok_dikurangi' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailsPembayaran()
    {
        return $this->hasMany(NotifikasiPesanan::class, 'transaksi_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(NotifikasiPesanan::class, 'transaksi_id');
    }

    public function diverifikasiOleh()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getSumberLabelAttribute(): string
    {
        return $this->sumber === 'website' ? 'Online' : 'Kasir';
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function getStatusLabelAttribute(): string
    {
        return OrderStatus::label($this->status);
    }

    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran
            ? (str_starts_with($this->bukti_pembayaran, 'http')
                ? $this->bukti_pembayaran
                : asset('storage/'.$this->bukti_pembayaran))
            : null;
    }

    public function linkWhatsapp(): ?string
    {
        $nomor = preg_replace('/[^0-9]/', '', (string) $this->no_telepon_pelanggan);
        if (! $nomor || strlen($nomor) < 8) {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62'.substr($nomor, 1);
        }

        $pesan = 'Halo, ini terkait pesanan '.$this->nomor_transaksi.'.';

        return 'https://wa.me/'.$nomor.'?text='.rawurlencode($pesan);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedUangDiterimaAttribute(): string
    {
        return 'Rp '.number_format($this->uang_diterima, 0, ',', '.');
    }

    public function getFormattedKembalianAttribute(): string
    {
        return 'Rp '.number_format($this->kembalian, 0, ',', '.');
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
        $prefix = 'TRX-'.date('Ymd').'-';
        $latest = self::where('nomor_transaksi', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNum = (int) substr($latest->nomor_transaksi, strlen($prefix));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix.str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
