<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const RECENT_ITEMS_LIMIT = 5;
    
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Get dashboard statistics and metrics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $monthlyMetrics = $this->dashboardService->getMonthlyMetrics();
            
            return $this->success([
                'statistics' => $this->dashboardService->getOverallStatistics(),
                'user_statistics' => $this->dashboardService->getUserStatistics(),
                'chart' => [
                    'labels' => $monthlyMetrics->pluck('month'),
                    'datasets' => $this->dashboardService->getChartDatasets($monthlyMetrics)
                ],
                'orders_by_status' => $this->dashboardService->getOrdersByStatus(),
                'latest_activity' => $this->dashboardService->getLatestActivity(self::RECENT_ITEMS_LIMIT),
            ], 'تم جلب البيانات بنجاح');
            
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
