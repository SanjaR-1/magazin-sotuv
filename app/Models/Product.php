<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'cost',
        'prtype_id'
    ];

    // Tovar turi (Kategoriyasi) bilan bog'lanish
    public function type(): BelongsTo
    {
        return $this->belongsTo(Prtype::class, 'prtype_id');
    }

    // Tovar qaysi buyurtmalarda borligi
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity') // Nechta sotilgani (3-mashq uchun kerak bo'ladi)
                    ->withTimestamps();
    }

    // Laravel 10/11 uchun dates ustunini casts orqali yozish tavsiya etiladi
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}