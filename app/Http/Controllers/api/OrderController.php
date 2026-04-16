<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {
        //...
    }
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());
        return new OrderResource($order);
    }
    public function changeStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:packing,on_way,delivered',
            'driver_id' => 'nullable|exists:drivers,id'
        ]);
        $order = $this->orderService->updateStatus(
            $order, 
            $request->status, 
            $request->driver_id
        );
        return new OrderResource($order);
    }
}