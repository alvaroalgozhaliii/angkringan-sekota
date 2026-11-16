<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'photo_path',
        'category_id',
        'is_best_seller',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
