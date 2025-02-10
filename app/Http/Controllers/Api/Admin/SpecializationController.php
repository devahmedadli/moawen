<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpecializationResource;
use App\Http\Requests\Api\Admin\StoreSpecializationRequest;
use App\Http\Requests\Api\Admin\UpdateSpecializationRequest;


class SpecializationController extends Controller
{

    protected array $allowedRelationships = [
        'category',
        'services',
        'freelancers',
    ];

    protected array $countableRelationships = [
        'freelancers',
        'services'
    ];

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Specialization::query();
            $query = $this->buildQuery($request, $query);

            $specializations = $query->paginate($request->get('per_page', 25));

            return $this->success(
                SpecializationResource::collection($specializations),
                'تم جلب التخصصات بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param StoreSpecializationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSpecializationRequest $request)
    {
        try {
            $specialization = Specialization::create($request->validated());
            return $this->success(
                new SpecializationResource($specialization),
                'تم إنشاء التخصص بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $id)
    {
        try {
            $query = Specialization::query();
            $query = $this->buildQuery($request, $query);
            $specialization = $query->findOrFail($id);
            return $this->success(
                new SpecializationResource($specialization),
                'تم جلب التخصص بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param UpdateSpecializationRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSpecializationRequest $request, string $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
            $specialization->update($request->validated());
            return $this->success(
                new SpecializationResource($specialization),
                'تم تحديث التخصص بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $specialization = Specialization::findOrFail($id);
            $specialization->delete();
            return $this->success(null, 'تم حذف التخصص بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
