<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Prtype_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    public function type()
    {
        return $this->belongsTo(PrType::class, 'Prtype_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}