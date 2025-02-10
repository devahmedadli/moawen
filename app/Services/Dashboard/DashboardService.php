<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\WithdrawalResource;

class DashboardService extends BaseDashboardService
{
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
        return [
            'orders'        => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'revenue'       => Order::whereBetween('created_at', [$startDate, $endDate])->sum('price'),
            'new_users'     => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_services'  => Service::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
    }

    /**
     * Get the overall statistics
     * 
     * @return array
     */
    public function getOverallStatistics(): array
    {
        return [

            'total_orders'      => Order::count(),
            'total_revenue'     => Order::sum('price'),
            'total_users'       => User::count(),
            'total_services'    => Service::count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'total_withdrawals' => Withdrawal::sum('amount'),
        ];
    }

    /**
     * Get the user statistics
     * 
     * @return array
     */

    public function getUserStatistics(): array
    {
        return [
            'freelancers'       => User::OfType('freelancer')->count(),
            'clients'           => User::OfType('client')->count(),
            'active_users'      => User::where('status', 'active')->count(),
            'inactive_users'    => User::where('status', 'inactive')->count(),
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

            'pending'       => Order::whereStatus('pending')->count(),
            'in_progress'   => Order::whereStatus('in_progress')->count(),
            'completed'     => Order::whereStatus('completed')->count(),
            'canceled'      => Order::whereStatus('canceled')->count(),

        ];
    }

    /**
     * Get the latest activity
     * 
     * @param int $limit
     * @return array
     */

    public function getLatestActivity(int $limit): array
    {
        return [
            'recent_orders'     => OrderResource::collection(Order::latest()->take($limit)->get()),
            'recent_users'      => UserResource::collection(User::latest()->take($limit)->get()),
            'recent_withdrawals' => WithdrawalResource::collection(Withdrawal::latest()->take($limit)->get()),
            'recent_services'   => ServiceResource::collection(Service::latest()->take($limit)->get()),
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
                'label' => 'الإيرادات',
                'data' => $metrics->pluck('revenue'),
            ],
            [
                'label' => 'المستخدمين الجدد',
                'data' => $metrics->pluck('new_users'),
            ],
            [
                'label' => 'الخدمات الجديدة',
                'data' => $metrics->pluck('new_services'),
            ]
        ];
    }
} 