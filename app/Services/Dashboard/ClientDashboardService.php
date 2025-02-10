<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClientDashboardService extends BaseDashboardService
{
    public function __construct(
        private readonly User $user
    ) {}

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
        $orders = Order::where('buyer_id', $this->user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'orders' => $orders->count(),
            'spending' => $orders->sum('price'),
        ];
    }

    /**
     * Get the monthly metrics for the last 12 months
     * 
     * @return Collection<array-key, array{month: string, orders: int, spending: float}>
     */
    public function getMonthlyMetrics(): Collection
    {
        $months = $this->getLastMonths();

        
        return $months->map(function($month) {
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $orders = Order::where('buyer_id', $this->user->id)
                ->whereBetween('created_at', [$startDate, $endDate]);

            return [
                'month' => $this->getArabicMonthName(Carbon::createFromFormat('Y-m', $month)->format('M')),
                'orders' => $orders->count(),
                'spending' => $orders->sum('price'),
            ];
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

            'total_orders' => Order::where('buyer_id', $this->user->id)->count(),
            'total_spending' => Order::where('buyer_id', $this->user->id)->sum('price'),
            'reviews_given' => Review::where('user_id', $this->user->id)->count(),
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
            'pending' => Order::where('buyer_id', $this->user->id)
                ->where('status', 'pending')->count(),
            'in_progress' => Order::where('buyer_id', $this->user->id)
                ->where('status', 'in_progress')->count(),
            'completed' => Order::where('buyer_id', $this->user->id)
                ->where('status', 'completed')->count(),
            'canceled' => Order::where('buyer_id', $this->user->id)
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
                'label' => 'الطلبات',
                'data' => $metrics->pluck('orders'),
            ],
            [
                'label' => 'المصروفات',
                'data' => $metrics->pluck('spending'),
            ]
        ];
    }
} 