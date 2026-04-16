<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\StatsService;
use App\Http\Resources\StatsResource;
class StatsController extends Controller
{
    public function __construct(protected StatsService $statsService)
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }
    public function __invoke()
    {
        $stats = $this->statsService->getGeneralStats();
        return new StatsResource($stats);
    }
}