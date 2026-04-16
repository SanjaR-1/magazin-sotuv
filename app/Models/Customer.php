<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Customer extends Model
{
    protected $fillable = [
        'user_id',   
        'first_name',
        'last_name',
        'phone_number'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}