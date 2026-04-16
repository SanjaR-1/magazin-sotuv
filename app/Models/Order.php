<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'driver_id',
        'address_id',
        'status',
        'picked_up_at',
        'delivered_at',
    ];
    protected $casts = [
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
    public const STATUS_PACKING = 1;
    public const STATUS_ON_WAY = 2;
    public const STATUS_DELIVERED = 3;
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function getPackingDurationAttribute()
    {
        if ($this->picked_up_at) {
            return $this->created_at->diffInMinutes($this->picked_up_at);
        }
        return $this->created_at->diffInMinutes(now());
    }
    public function getDeliveryDurationAttribute()
    {
        if (!$this->picked_up_at) {
            return 0;
        }
        if ($this->delivered_at) {
            return $this->picked_up_at->diffInMinutes($this->delivered_at);
        }
        return $this->picked_up_at->diffInMinutes(now());
    }
}