<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\Freelancer\StoreServiceRequest;
use App\Http\Requests\Api\Freelancer\UpdateServiceRequest;

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
            $query = $query->where('user_id', auth()->user()->id);
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
        try {

            $validated = $request->validated();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $extension = $thumbnail->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                // Store the file in the public disk under services/thumbnails directory
                $path = $thumbnail->storeAs('services/thumbnails', $filename, 'public');

                // Update the validated data with the thumbnail path
                $validated['thumbnail'] = $path;
            }

            // dd($validated);
            $service = Service::create($validated);

            return $this->success(new ServiceResource($service), 'تم إضافة الخدمة بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $service = $this->getServiceWithRelations($request, $id);
            return $this->success(new ServiceResource($service), 'تم عرض الخدمة بنجاح');
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
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $service = Service::findOrFail($id);
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $extension = $thumbnail->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                // Store the file in the public disk under services/thumbnails directory
                $path = $thumbnail->storeAs('services/thumbnails', $filename, 'public');

                // delete the old thumbnail
                Storage::disk('public')->delete($service->thumbnail);

                // Update the validated data with the thumbnail path
                $validated['thumbnail'] = $path;
            }

            $service->update($validated);
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
        $service = Service::find($id);
        if (!$service) {
            return $this->error('الخدمة غير موجودة', 404);
        }
        if ($service->user_id !== auth()->user()->id) {
            return $this->error('غير مصرح بالوصول إلى هذه الخدمة', 403);
        }
        $service->delete();
        return $this->success(null, 'تم حذف الخدمة بنجاح');
    }
}
