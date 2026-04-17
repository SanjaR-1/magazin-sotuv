<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'driver_id' => $this['driver_id'],
            'driver_name' => $this['driver_name'],
            'phone' => $this['phone'],
            'delivered_orders_count' => $this['delivered_orders_count'],
            'unique_customers_count' => $this['unique_customers_count'],
            'total_items_delivered' => $this['total_items_delivered'],
        ];
    }
}