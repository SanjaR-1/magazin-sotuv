<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
    ];

    /**
     * Tizim foydalanuvchisi (User) bilan bog'liqlik.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Haydovchiga biriktirilgan buyurtmalar.
     * Endi 'order_driver' pivot jadvali shart emas.
     */
    public function orders(): HasMany
    {
        // 'driver_id' ustuni to'g'ridan-to'g'ri 'orders' jadvalida bo'ladi
        return $this->hasMany(Order::class, 'driver_id');
    }

    /**
     * Accessor: To'liq ism-sharif.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}