<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name'];

    /**
     * Regionga tegishli manzillar.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}