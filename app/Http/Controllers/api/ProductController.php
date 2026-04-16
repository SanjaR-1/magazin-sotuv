<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Casts\Json;
class ProductController extends Controller
{
    public  function __construct(protected ProductService $productService){
        $this->middleware('auth:sanctum')->except(['index','show']);   
        $this->middleware('check.admin')->only(['store','update','destroy']);
    }
    public function index()
    {
        $products=$this->productService->getAllProducts();
        return ProductResource::collection($products);
    }
    public function store(StoreProductRequest $request)
    {
        $product=$this->productService->createProduct($request->validated());
        return response()->json([
            'success'=>true,
            'message'=>'muvaffaqiyatli yaratildi!',
            'data'=> new ProductResource($product),
        ],201);
    }
    public function show(int $id)
    {
        $product=$this->productService->getProductById($id);
        return new ProductResource($product);
    }
    public function update(UpdateProductRequest $request, int $id)
    {
        $product=$this->productService->updateProduct($id, $request->validated());
        return response()->json([
            'success'=>true,
            'message'=>'muvaffaqiyatli o\'zgardi',
            'data'=> new ProductResource($product)
        ],200);
}
    public function destroy(int $id)
    {
        $this->productService->deleteProduct($id);
        return response()->json([
            'success'=>true,
            'message'=>'muvaffaqiyatli o\'chirildi'
        ],200);
    }
}
