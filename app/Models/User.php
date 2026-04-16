<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
}