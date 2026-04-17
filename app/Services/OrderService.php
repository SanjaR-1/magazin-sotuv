<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function paginateForUser(User $user, int $perPage = 10)
    {
        $query = Order::with(['customer', 'driver', 'region', 'items']);

        if ($user->role === 'customer') {
            $query->where('customer_id', $user->id);
        }

        if ($user->role === 'driver') {
            $query->where('driver_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    public function store(array $data, User $user): Order
    {
        return DB::transaction(function () use ($data, $user) {
            $order = Order::create([
                'customer_id' => $user->id,
                'driver_id' => null,
                'region_id' => $data['region_id'],
                'customer_first_name' => $user->first_name,
                'customer_last_name' => $user->last_name,
                'customer_phone' => $user->phone,
                'delivery_address' => $data['delivery_address'],
                'status' => Order::STATUS_PACKING,
                'ordered_at' => now(),
                'packed_at' => now(),
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::with('type')->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'stock' => ["{$product->name} omborda yetarli emas"],
                    ]);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_type_name' => $product->type->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order->load(['customer', 'driver', 'region', 'items']);
        });
    }

    public function updateStatus(array $data, Order $order, User $user): Order
    {
        $status = $data['status'];

        if ($status === Order::STATUS_PACKING) {
            if (! $user->isAdmin()) {
                throw ValidationException::withMessages([
                    'status' => ['Faqat admin packing qila oladi'],
                ]);
            }

            $order->update([
                'status' => Order::STATUS_PACKING,
                'packed_at' => now(),
            ]);
        }

        if ($status === Order::STATUS_ON_WAY) {
            if (! $user->isAdmin() && ! $user->isDriver()) {
                throw ValidationException::withMessages([
                    'status' => ['Faqat admin yoki driver on_way qila oladi'],
                ]);
            }

            $driverId = $data['driver_id'] ?? $user->id;

            $driver = User::query()
                ->where('id', $driverId)
                ->where('role', 'driver')
                ->first();

            if (! $driver) {
                throw ValidationException::withMessages([
                    'driver_id' => ['Driver topilmadi yoki roli driver emas'],
                ]);
            }

            $order->update([
                'driver_id' => $driver->id,
                'status' => Order::STATUS_ON_WAY,
                'on_way_at' => now(),
            ]);
        }

        if ($status === Order::STATUS_DELIVERED) {
            if (! $user->isAdmin() && ! $user->isDriver()) {
                throw ValidationException::withMessages([
                    'status' => ['Faqat admin yoki driver delivered qila oladi'],
                ]);
            }

            if ($user->isDriver() && $order->driver_id !== $user->id) {
                throw ValidationException::withMessages([
                    'driver_id' => ['Bu order boshqa driverga tegishli'],
                ]);
            }

            $order->update([
                'status' => Order::STATUS_DELIVERED,
                'delivered_at' => now(),
            ]);
        }

        return $order->fresh()->load(['customer', 'driver', 'region', 'items']);
    }

    public function pendingDeliveries(int $perPage = 10)
    {
        return Order::with(['customer', 'driver', 'region', 'items'])
            ->whereIn('status', [Order::STATUS_PACKING, Order::STATUS_ON_WAY])
            ->orderBy('ordered_at', 'asc')
            ->paginate($perPage);
    }
}