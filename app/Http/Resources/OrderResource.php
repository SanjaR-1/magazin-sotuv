<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer_id' => $this->customer_id,
            'customer_name' => trim($this->customer_first_name . ' ' . $this->customer_last_name),
            'customer_phone' => $this->customer_phone,

            'driver_id' => $this->driver_id,
            'driver_name' => $this->whenLoaded('driver', function () {
                return $this->driver
                    ? trim($this->driver->first_name . ' ' . $this->driver->last_name)
                    : null;
            }),

            'region_id' => $this->region_id,
            'region_name' => $this->whenLoaded('region', fn () => $this->region?->name),
            'delivery_address' => $this->delivery_address,

            'status' => $this->status,
            'waiting_minutes' => $this->waiting_minutes,

            'ordered_at' => $this->ordered_at?->toDateTimeString(),
            'packed_at' => $this->packed_at?->toDateTimeString(),
            'on_way_at' => $this->on_way_at?->toDateTimeString(),
            'delivered_at' => $this->delivered_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),

            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}