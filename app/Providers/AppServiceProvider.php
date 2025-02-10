<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Dashboard\ClientDashboardService;
use App\Services\Dashboard\FreelancerDashboardService;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientDashboardService::class, function ($app) {
            return new ClientDashboardService(Auth::user());
        });

        $this->app->bind(FreelancerDashboardService::class, function ($app) {
            return new FreelancerDashboardService(Auth::user());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
