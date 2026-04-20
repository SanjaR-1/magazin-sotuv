<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\ReportService;
use App\Http\Resources\DriverStatResource;
use App\Http\Resources\ProductSaleResource;
use App\Http\Resources\RegionSaleResource;
use App\Http\Controllers\Controller;
class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}
    public function driverStats(): JsonResponse{
        $data = $this->reportService->driverStats();
        return response()->json([
            'success' => true,
            'data' => DriverStatResource::collection($data),
        ]);
    }
    public function productSales(): JsonResponse{
        $data = $this->reportService->productSales();

        return response()->json([
            'success' => true,
            'data' => ProductSaleResource::collection($data),
        ]);
    }
    public function regionSales(): JsonResponse{
        $data = $this->reportService->regionSales();
        return response()->json([
            'success' => true,
            'data' => RegionSaleResource::collection($data),
        ]);
    }
}