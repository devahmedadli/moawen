<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Admin\StoreClientRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ClientController
 * @package App\Http\Controllers\Api\Admin
 *
 * @group Admin Client Management
 *
 * APIs for managing clients in the admin panel
 */
class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        try {
            $query = User::ofType('client');

            $query = $this->buildQuery($request, $query);

            $clients = $query->paginate($request->get('per_page', 25));

            return $this->success(
                new UserCollection($clients),
                'تم جلب المستخدمين بنجاح',
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $validated = $request->validated();

            $client = User::create([
                ...$validated,
                'username'  => '',
                'role'      => 'client'
            ]);

            $client = User::find($client->id);
            return $this->success(
                new UserResource($client),
                'تم إنشاء العميل بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */     
    public function show(Request $request, string $id)
    {
        try {
            $query = User::ofType('client');
            $query = $this->buildQuery($request, $query);
            $client = $query->findOrFail($id);
            return $this->success(new UserResource($client), 'تم جلب العميل بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $client = User::ofType('client')->find($id);
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'phone' => 'sometimes|string|unique:users,phone,' . $id,
                'username' => 'sometimes|string|unique:users,username,' . $id,
                'password' => 'sometimes|string|min:6',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }
            $client->update($validated);
            return $this->success(new UserResource($client), 'تم تحديث العميل بنجاح');
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
            $client = User::ofType('client')->findOrFail($id);
            $client->delete();
            return $this->success(null, 'تم حذف العميل بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
