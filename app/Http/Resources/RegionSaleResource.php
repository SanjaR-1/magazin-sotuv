<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'region_id' => $this['region_id'],
            'region_name' => $this['region_name'],
            'orders_count' => $this['orders_count'],
        ];
    }
}