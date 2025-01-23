<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Admin\StoreServiceRequest;
use App\Http\Requests\Api\Admin\UpdateServiceRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceController extends Controller
{

    protected array $allowedRelationships = [
        'freelancer',
        'reviews',
        'category',
        'specialization',
        'orders',
        'offers'
    ];

    protected array $countableRelationships = [
        'reviews',
        'orders',
        'offers'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Service::query();
            $query = $this->buildQuery($request, $query);
            $services = $query->paginate($request->get('per_page', 25));

            return $this->success(
                ServiceResource::collection($services),
                'تم جلب الخدمات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());
        return $this->success(new ServiceResource($service), 'تم اضافة الخدمة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        try {
            $service = $this->getServiceWithRelations($request, $id);
            $this->trackServiceView($service);

            return $this->success(
                new ServiceResource($service), 
                'تم جلب الخدمة بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get the service with the specified relationships.
     */
    protected function getServiceWithRelations(Request $request, string $id): Service
    {
        return $this->buildQuery($request, Service::query())->findOrFail($id);
    }

    /**
     * Track the service view.
     */
    protected function trackServiceView(Service $service): void
    {
        // Get IP address and create a unique key combining IP and service ID
        $ip = request()->ip();
        $viewKey = "service_view_{$service->id}_{$ip}";
        
        // Use cache instead of session for API
        if (!cache()->has($viewKey)) {
            cache([$viewKey => now()], now()->addDay());
            $service->increment('views');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->update($request->validated());
            return $this->success(new ServiceResource($service), 'تم تعديل الخدمة بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return $this->success(null, 'تم حذف الخدمة بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
