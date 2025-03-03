<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\ClientDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ClientDashboardService $dashboardService
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $monthlyMetrics = $this->dashboardService->getMonthlyMetrics();
            
            return $this->success([
                'statistics' => $this->dashboardService->getOverallStatistics(),
                'chart' => [
                    'labels' => $monthlyMetrics->pluck('month'),
                    'datasets' => $this->dashboardService->getChartDatasets($monthlyMetrics)
                ],
                'orders_by_status' => $this->dashboardService->getOrdersByStatus(),
            ], 'تم جلب البيانات بنجاح');
            
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
