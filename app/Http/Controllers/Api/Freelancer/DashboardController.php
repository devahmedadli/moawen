<?php

namespace App\Http\Controllers\Api\Freelancer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class  DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            
            $user = Auth::user();
            
            // Get the last 12 months
            $months = collect(range(0, 11))->map(function($month) {
                return now()->subMonths($month)->format('Y-m');
            })->reverse();


            // Get all services for this freelancer
            $services = \App\Models\Service::where('user_id', $user->id)->get();
            $serviceIds = $services->pluck('id');

            // Collect metrics for each month
            $metrics = $months->map(function($month) use ($serviceIds) {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

                // Get orders in this period
                $orders = \App\Models\Order::whereIn('service_id', $serviceIds)
                    ->whereBetween('created_at', [$startDate, $endDate]);

                // Get views in this period (assuming you have a views column in services table)
                $views = \App\Models\Service::whereIn('id', $serviceIds)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('views');

                return [
                    'month' => $this->getArabicMonthName(\Carbon\Carbon::createFromFormat('Y-m', $month)->format('M')), // Arabic month name
                    'views' => $views,
                    'orders' => $orders->count(),
                    'revenue' => $orders->sum('total_amount'),
                ];
            });

            // Get overall statistics
            $totalOrders = \App\Models\Order::whereIn('service_id', $serviceIds)->count();
            $totalRevenue = \App\Models\Order::whereIn('service_id', $serviceIds)->sum('total_amount');
            $totalViews = $services->sum('views');
            $reviewsCount = \App\Models\Review::where('user_id', $user->id)->count();

            return $this->success([
                // Overall statistics
                'statistics' => [
                    'total_orders' => $totalOrders,
                    'total_revenue' => $totalRevenue,
                    'total_views' => $totalViews,
                    'reviews_count' => $reviewsCount,
                ],
                // Chart data
                'chart' => [
                    'labels' => $metrics->pluck('month'),
                    'datasets' => [
                        [
                            'label' => 'المشاهدات',
                            'data' => $metrics->pluck('views'),
                        ],
                        [
                            'label' => 'الطلبات',
                            'data' => $metrics->pluck('orders'),
                        ],
                        [
                            'label' => 'الإيرادات',
                            'data' => $metrics->pluck('revenue'),
                        ]
                    ]
                ],
                // Orders by status
                'orders_by_status' => [
                    'pending' => \App\Models\Order::whereIn('service_id', $serviceIds)
                        ->where('status', 'pending')->count(),
                    'in_progress' => \App\Models\Order::whereIn('service_id', $serviceIds)
                        ->where('status', 'in_progress')->count(),
                    'completed' => \App\Models\Order::whereIn('service_id', $serviceIds)
                        ->where('status', 'completed')->count(),
                    'canceled' => \App\Models\Order::whereIn('service_id', $serviceIds)
                        ->where('status', 'canceled')->count(),
                ],
            ], 'تم جلب البيانات بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    protected function getArabicMonthName($month)
    {
        $arMonths = [
            "Jan" => "يناير",
            "Feb" => "فبراير",
            "Mar" => "مارس",
            "Apr" => "أبريل",
            "May" => "مايو",
            "Jun" => "يونيو",
            "Jul" => "يوليو",
            "Aug" => "أغسطس",
            "Sep" => "سبتمبر",
            "Oct" => "أكتوبر",
            "Nov" => "نوفمبر",
            "Dec" => "ديسمبر",
        ];
        return $arMonths[$month];
    }
}
