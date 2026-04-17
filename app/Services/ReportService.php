<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReportService
{
    public function driverStats(): Collection
    {
        return User::where('role', 'driver')
            ->withCount([
                'driverOrders as delivered_orders_count' => function ($q) {
                    $q->where('status', 'delivered');
                }
            ])
            ->get()
            ->map(function ($driver) {
                $uniqueCustomers = Order::where('driver_id', $driver->id)
                    ->where('status', 'delivered')
                    ->distinct('customer_id')
                    ->count('customer_id');

                $totalItems = OrderItem::whereHas('order', function ($q) use ($driver) {
                    $q->where('driver_id', $driver->id)
                        ->where('status', 'delivered');
                })->sum('quantity');

                return [
                    'driver_id' => $driver->id,
                    'driver_name' => trim($driver->first_name . ' ' . $driver->last_name),
                    'phone' => $driver->phone,
                    'delivered_orders_count' => $driver->delivered_orders_count,
                    'unique_customers_count' => $uniqueCustomers,
                    'total_items_delivered' => $totalItems,
                ];
            });
    }

    public function productSales()
    {
        return OrderItem::select(
                'product_id',
                'product_name',
                'product_type_name',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->whereHas('order', function ($q) {
                $q->where('status', 'delivered');
            })
            ->groupBy('product_id', 'product_name', 'product_type_name')
            ->orderByDesc('total_sold')
            ->get();
    }

    public function regionSales(): Collection
    {
        return Order::select(
                'region_id',
                DB::raw('COUNT(*) as orders_count')
            )
            ->with('region:id,name')
            ->where('status', 'delivered')
            ->groupBy('region_id')
            ->orderByDesc('orders_count')
            ->get()
            ->map(function ($item) {
                return [
                    'region_id' => $item->region_id,
                    'region_name' => $item->region?->name,
                    'orders_count' => $item->orders_count,
                ];
            });
    }
}