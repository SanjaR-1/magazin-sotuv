<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PACKING = 'packing';
    public const STATUS_ON_WAY = 'on_way';
    public const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'region_id',
        'customer_first_name',
        'customer_last_name',
        'customer_phone',
        'delivery_address',
        'status',
        'ordered_at',
        'packed_at',
        'on_way_at',
        'delivered_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'packed_at' => 'datetime',
        'on_way_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected $appends = [
        'waiting_minutes',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getWaitingMinutesAttribute()
    {
        $from = $this->ordered_at ?? $this->created_at;
        return $from ? $from->diffInMinutes(now()) : null;
    }
}