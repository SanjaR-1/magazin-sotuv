<?php

namespace App\Services;

use App\Models\Region;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * 3-MASHQ: Umumiy hisobotni yig'ish
     */
    public function getGeneralStats(): array
    {
        return [
            // Hududlar bo'yicha buyurtmalar soni
            'by_regions' => Region::withCount(['addresses as orders_count' => function ($query) {
                $query->has('orders');
            }])->get(),

            // Top 5 ta mahsulot (Pivotdagi quantity bo'yicha)
            'top_products' => Product::select('products.id', 'products.name')
                ->join('order_product', 'products.id', '=', 'order_product.product_id')
                ->selectRaw('SUM(order_product.quantity) as total_sold')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get(),

            // Eng yaxshi 3 ta haydovchi
            'top_drivers' => Order::where('status', 'delivered')
                ->select('driver_id', DB::raw('count(*) as delivered_count'))
                ->with(['driver:id,first_name,last_name'])
                ->groupBy('driver_id')
                ->orderByDesc('delivered_count')
                ->take(3)
                ->get(),
            
            // Umumiy tushum
            'total_revenue' => DB::table('order_product')
                ->join('products', 'order_product.product_id', '=', 'products.id')
                ->selectRaw('SUM(order_product.quantity * products.cost) as revenue')
                ->value('revenue') ?? 0
        ];
    }
}