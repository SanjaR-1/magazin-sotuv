<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}
    public function index(Request $request){
        $perPage = (int) $request->get('per_page', 10);
        $products = $this->productService->paginate($perPage);
        return ProductResource::collection($products);
    }
    public function store(StoreProductRequest $request): JsonResponse{
        $product = $this->productService->store($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }
    public function show(Product $product): ProductResource{
        $product = $this->productService->show($product);
        return new ProductResource($product);
    }
    public function update(UpdateProductRequest $request, Product $product): JsonResponse{
        $product = $this->productService->update($request->validated(), $product);
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product),
        ]);
    }
    public function destroy(Product $product): JsonResponse{
        $result = $this->productService->delete($product);
        return response()->json([
            'success' => true,
            'message' => $result['message'],
        ]);
    }
}