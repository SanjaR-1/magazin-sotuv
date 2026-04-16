<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_id' => auth()->user()->customer->id,
                'address_id'  => $data['address_id'],
                'status'      => 'packing',
            ]);
            foreach ($data['items'] as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity']
                ]);
            }
            return $order->load(['products', 'address.region']);
        });
    }
    public function updateStatus(Order $order, string $status, $driverId = null)
    {
        $payload = ['status' => $status];
        if ($driverId) {
            $payload['driver_id'] = $driverId;
        }
        if ($status === 'on_way') {
            $payload['picked_up_at'] = now();
        } elseif ($status === 'delivered') {
            $payload['delivered_at'] = now();
        }
        $order->update($payload);
        return $order;
    }
}