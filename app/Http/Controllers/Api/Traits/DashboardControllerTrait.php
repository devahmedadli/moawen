<?php

namespace App\Http\Controllers\Api\Traits;

use App\Services\Dashboard\BaseDashboardService;
use Illuminate\Http\JsonResponse;

trait DashboardControllerTrait
{
    protected function getDashboardData(BaseDashboardService $service): JsonResponse
    {
        try {
            $monthlyMetrics = $service->getMonthlyMetrics();
            return $this->success([
                'statistics' => $service->getOverallStatistics(),
                'chart' => [
                    'labels' => $monthlyMetrics->pluck('month'),
                    'datasets' => $service->getChartDatasets($monthlyMetrics)
                ],
                'orders_by_status' => $service->getOrdersByStatus(),
            ], 'تم جلب البيانات بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
