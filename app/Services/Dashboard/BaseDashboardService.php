<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class BaseDashboardService
{
    protected const MONTHS_RANGE = 12;
    
    protected array $arabicMonths = [
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

    abstract protected function getMetricsForMonth(string $month, Carbon $startDate, Carbon $endDate): array;
    abstract public function getOverallStatistics(): array;
    abstract public function getOrdersByStatus(): array;
    abstract public function getChartDatasets(Collection $metrics): array;

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

            return array_merge(
                ['month' => $this->getArabicMonthName(Carbon::createFromFormat('Y-m', $month)->format('M'))],
                $this->getMetricsForMonth($month, $startDate, $endDate)
            );
        });
    }

    /**
     * Get the last 12 months
     * 
     * @return Collection<array-key, string>
     */
    protected function getLastMonths(): Collection
    {
        return collect(range(0, self::MONTHS_RANGE - 1))

            ->map(fn($month) => now()->subMonths($month)->format('Y-m'))
            ->reverse();
    }

    /**
     * Get the Arabic month name
     * 
     * @param string $month
     * @return string
     */
    protected function getArabicMonthName(string $month): string
    {
        return $this->arabicMonths[$month];
    }
} 