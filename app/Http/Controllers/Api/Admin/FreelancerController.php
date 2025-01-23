<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\Admin\StoreFreelancerRequest;

/**
 * @group Admin Freelancer Management
 *
 * APIs for managing freelancers
 */
class FreelancerController extends Controller
{
    // use HasQueryBuilder;

    protected array $allowedRelationships = [
        'services',
        'services.category',
        'reviews',
        'orders',
        'purchases',
        'experienceTimeline'
    ];

    protected array $countableRelationships = [
        'services',
        'reviews',
        'orders',
        'purchases'
    ];

    protected string $defaultSortField = 'created_at';
    protected string $defaultSortOrder = 'desc';


    public function index(Request $request)
    {
        try {
            $query = User::ofType('freelancer');
            $query = $this->buildQuery($request, $query);

            $freelancers = $query->paginate($request->get('per_page', 25));

            return $this->success(
                UserResource::collection($freelancers),
                'تم جلب المستخدمين بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreFreelancerRequest $request)
    {
        try {
            $validated = $request->validated();

            // dd([...$validated,
            //     'role' => 'freelancer']);
            $freelancer = User::create([
                ...$validated,
                'username'  => '',
                'role'      => 'freelancer'
            ]);

            $freelancer = User::find($freelancer->id);

            return $this->success(
                new UserResource($freelancer),
                'تم إنشاء المستخدم بنجاح'
            );
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
            $query = User::ofType('freelancer');

            $query = $this->buildQuery($request, $query);

            $freelancer = $query->findOrFail($id);

            return $this->success(
                new UserResource($freelancer),
                'تم جلب المستخدم بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $freelancer = User::ofType('freelancer')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            // Add other validation rules as needed
        ]);

        $freelancer->update($validated);

        return $this->success(
            new UserResource($freelancer),
            'تم تحديث المستخدم بنجاح'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $freelancer = User::ofType('freelancer')->findOrFail($id);

            $freelancer->delete();

            return $this->success(
                null,
                'تم حذف المستخدم بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
