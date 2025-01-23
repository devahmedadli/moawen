<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

            // Collect metrics for each month
            $metrics = $months->map(function($month) use ($user) {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

                // Get orders in this period
                $orders = \App\Models\Order::where('buyer_id', $user->id)
                    ->whereBetween('created_at', [$startDate, $endDate]);

                return [
                    'month' => $this->getArabicMonthName(\Carbon\Carbon::createFromFormat('Y-m', $month)->format('M')),
                    'orders' => $orders->count(),
                    'spending' => $orders->sum('price'),
                ];
            });

            // Get overall statistics
            $totalOrders = \App\Models\Order::where('buyer_id', $user->id)->count();
            $totalSpending = \App\Models\Order::where('buyer_id', $user->id)->sum('price');
            $reviewsGiven = \App\Models\Review::where('user_id', $user->id)->count();

            return $this->success([
                // Overall statistics
                'statistics' => [
                    'total_orders' => $totalOrders,
                    'total_spending' => $totalSpending,
                    'reviews_given' => $reviewsGiven,
                ],
                // Chart data
                'chart' => [
                    'labels' => $metrics->pluck('month'),
                    'datasets' => [
                        [
                            'label' => 'الطلبات',
                            'data' => $metrics->pluck('orders'),
                        ],
                        [
                            'label' => 'المصروفات',
                            'data' => $metrics->pluck('spending'),
                        ]
                    ]
                ],
                // Orders by status
                'orders_by_status' => [
                    'pending' => \App\Models\Order::where('buyer_id', $user->id)
                        ->where('status', 'pending')->count(),
                    'in_progress' => \App\Models\Order::where('buyer_id', $user->id)
                        ->where('status', 'in_progress')->count(),
                    'completed' => \App\Models\Order::where('buyer_id', $user->id)
                        ->where('status', 'completed')->count(),
                    'canceled' => \App\Models\Order::where('buyer_id', $user->id)
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
