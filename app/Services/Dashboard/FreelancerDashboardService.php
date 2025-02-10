<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FreelancerDashboardService extends BaseDashboardService
{
    private Collection $services;
    private Collection $serviceIds;

    public function __construct(
        private readonly User $user
    ) {
        $this->services = Service::where('user_id', $this->user->id)->get();
        $this->serviceIds = $this->services->pluck('id');
    }
    /**
     * Get the metrics for a specific month
     * 
     * @param string $month
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function getMetricsForMonth(string $month, Carbon $startDate, Carbon $endDate): array
    {
        $orders = Order::whereIn('service_id', $this->serviceIds)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $views = Service::whereIn('id', $this->serviceIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('views');

        return [
            'views'     => $views,
            'orders'    => $orders->count(),
            'revenue'   => $orders->sum('price'),
        ];
    }
    /**
     * Get the monthly metrics for the last 12 months
     * 
     * @return Collection<array-key, array{month: string, views: int, orders: int, revenue: float}>
     */
    public function getMonthlyMetrics(): Collection
    {
        $months = $this->getLastMonths();


        return $months->map(function ($month) {
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            return array_merge(
                ['month' => $this->getArabicMonthName(Carbon::createFromFormat('Y-m', $month)->format('M'))],
                $this->getMetricsForMonth($month, $startDate, $endDate)
            );
        });
    }
    /**
     * Get the overall statistics
     * 
     * @return array
     */
    public function getOverallStatistics(): array
    {
        return [

            'total_orders'  => Order::whereIn('service_id', $this->serviceIds)->count(),
            'total_revenue' => Order::whereIn('service_id', $this->serviceIds)->sum('price'),
            'total_views'   => $this->services->sum('views'),
            'reviews_count' => Review::where('user_id', $this->user->id)->count(),
        ];
    }
    /**
     * Get the orders by status
     * 
     * @return array
     */
    public function getOrdersByStatus(): array

    {
        return [
            'pending'       => Order::whereIn('service_id', $this->serviceIds)
                ->where('status', 'pending')->count(),
            'in_progress'   => Order::whereIn('service_id', $this->serviceIds)
                ->where('status', 'in_progress')->count(),
            'completed'     => Order::whereIn('service_id', $this->serviceIds)
                ->where('status', 'completed')->count(),
            'canceled'      => Order::whereIn('service_id', $this->serviceIds)
                ->where('status', 'canceled')->count(),
        ];
    }
    /**
     * Get the chart datasets
     * 
     * @param Collection $metrics
     * @return array
     */

    public function getChartDatasets(Collection $metrics): array
    {
        return [
            [
                'label' => 'المشاهدات',
                'data'  => $metrics->pluck('views'),
            ],
            [
                'label' => 'الطلبات',
                'data'  => $metrics->pluck('orders'),
            ],
            [
                'label' => 'الإيرادات',
                'data'  => $metrics->pluck('revenue'),
            ]
        ];
    }
}
