<?php

namespace App\Services;

use App\Models\Product;

class ProductService{
    public function paginate(int $perPage = 10){
        return Product::with('type')
            ->latest()
            ->paginate($perPage);
    }
    public function show(Product $product): Product{
        return $product->load('type');
    }
    public function store(array $data): Product{
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('products', 'public');
        }
        $product = Product::create($data);
        return $product->load('type');
    }
    public function update(array $data, Product $product): Product{
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('products', 'public');
        }
        $product->update($data);
        return $product->load('type');
    }
    public function delete(Product $product): array{
        $product->delete();

        return [
            'message' => 'Product deleted successfully',
        ];
    }
}