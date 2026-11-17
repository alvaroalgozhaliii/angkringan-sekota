<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'customer_name',
        'avatar',
        'review',
        'rating',
        'source',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Scope untuk ulasan yang aktif / disetujui tampil
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope urutan terbaru
     */
    public function scopeTerbaru($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * Helper inisial nama untuk avatar
     */
    public function getInisialAttribute(): string
    {
        $words = explode(' ', trim($this->customer_name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            if (!empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
        }
        return $initials ?: 'AS';
    }

    /**
     * Helper waktu relatif dalam bahasa Indonesia
     */
    public function getWaktuFormatAttribute(): string
    {
        if (!$this->created_at) {
            return 'Baru saja';
        }
        return $this->created_at->diffForHumans();
    }
}
