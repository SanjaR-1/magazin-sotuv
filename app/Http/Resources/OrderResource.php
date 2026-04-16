<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'customer'   => $this->customer->first_name . ' ' . $this->customer->last_name,
            'address'    => $this->address->name,
            'region'     => $this->address->region->name,
            'status'     => $this->status,
            'ordered_at' => $this->created_at->format('Y-m-d H:i:s'),
            'products'   => $this->products->map(function ($product) {
                return [
                    'name'     => $product->name,
                    'price'    => $product->cost,
                    'quantity' => $product->pivot->quantity,
                    'total'    => $product->cost * $product->pivot->quantity
                ];
            }),
            'total_amount' => $this->products->sum(function($p) {
                return $p->cost * $p->pivot->quantity;
            })
        ];
    }
}