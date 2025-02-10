<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\FreelancerDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Traits\DashboardControllerTrait;

class DashboardController extends Controller
{
    use DashboardControllerTrait;

    public function __construct(
        private readonly FreelancerDashboardService $dashboardService
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        return $this->getDashboardData($this->dashboardService);
    }
}
