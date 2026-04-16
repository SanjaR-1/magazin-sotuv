<?php
namespace App\Services;
use App\Models\Product;
class ProductService
{
    public function getAllProducts()
    {
        return Product::with('type')->get();
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }
    public function getProductById(int $id){
        return Product::with('type')->findOrFail($id);
    }
    public function updateProduct(int $id,array $data){
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }
    public function deleteProduct(int $id){
        $product= Product::findOrFail($id);
        return $product->delete();
    }
}