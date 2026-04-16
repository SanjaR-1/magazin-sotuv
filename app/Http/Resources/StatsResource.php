<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'regions_report' => $this['by_regions']->map(fn($r) => [
                'region_name' => $r->name,
                'total_orders' => $r->orders_count
            ]),
            'popular_products' => $this['top_products']->map(fn($p) => [
                'product_name' => $p->name,
                'quantity_sold' => (int) $p->total_sold
            ]),
            'best_drivers' => $this['top_drivers']->map(fn($d) => [
                'driver_name' => $d->driver->first_name . ' ' . $d->driver->last_name,
                'orders_completed' => $d->delivered_count
            ]),
            'financials' => [
                'total_revenue' => number_format($this['total_revenue'], 2) . ' USD'
            ]
        ];
    }
}