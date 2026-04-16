<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable=[ 
        'name',
        'region_id'
    ];
    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
