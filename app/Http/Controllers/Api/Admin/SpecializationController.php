<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpecializationResource;
use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;


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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialization $specialization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialization $specialization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationRequest $request, Specialization $specialization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        //
    }
}
