<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prtype extends Model
{
    protected $fillable = ['name'];

    // 3-mashq uchun: Ushbu turdagi barcha mahsulotlarni olish
    public function products():HasMany
    {
        return $this->hasMany(Product::class, 'prtype_id');
    }
}