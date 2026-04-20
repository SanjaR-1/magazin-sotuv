<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Controllers\Controller;

class OrderController extends Controller{
    public function __construct(
        protected OrderService $orderService
    ) {}
    public function index(Request $request){
        $perPage = (int) $request->get('per_page', 10);
        $orders = $this->orderService->paginateForUser($request->user(), $perPage);
        return OrderResource::collection($orders);
    }
    public function store(StoreOrderRequest $request): JsonResponse{
        $order = $this->orderService->store(
            $request->validated(),
            $request->user()
        );
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => new OrderResource($order),
        ], 201);
    }
    public function packingDeliveries(Request $request){
        $perPage = (int) $request->get('per_page', 10);
        $orders = $this->orderService->packingDeliveries($perPage);
        return OrderResource::collection($orders);
    }
    public function takeOrder(Order $order, UpdateOrderStatusRequest $request):JsonResponse{
        $order=$this->orderService->takeOrder($order,$request->user());
        return response()->json([
            'succes'=> true,
            'message'=>"Driver took the order successfully!",
            'data'=> new OrderResource($order)
        ]); 
    }
    public function deliverOrder(Order $order, UpdateOrderStatusRequest $request):JsonResponse{
        $order=$this->orderService->deliverOrder($order,$request->user());
        return response()->json([
            'succes'=> true,
            'message'=>"Driver gave the order successfully!",
            'data'=> new OrderResource($order)
        ]); 
    }
}