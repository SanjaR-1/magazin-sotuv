<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prtype_id' => $this->prtype_id,
            'prtype_name' => $this->whenLoaded('type', fn () => $this->type?->name),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}