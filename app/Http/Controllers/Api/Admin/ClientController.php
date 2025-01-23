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
     * Update client details
     * 
     * Update the specified client's information.
     *
     * @urlParam id required The ID of the client. Example: 1
     * @bodyParam name string optional The name of the client. Example: John Doe
     * @bodyParam email string optional The email address of the client. Must be unique. Example: john@example.com
     * @bodyParam password string optional The new password for the client account. Minimum 6 characters. Example: newpassword123
     * @bodyParam phone string optional The phone number of the client. Must be unique. Example: +1234567890
     * @bodyParam username string optional The username for the client. Must be unique. Example: john-doe
     *
     * @response status=200 scenario="Success" {
     *     "status": true,
     *     "message": "تم تحديث العميل بنجاح",
     *     "data": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "john@example.com",
     *         "phone": "+1234567890",
     *         "username": "john-doe",
     *         "type": "client",
     *         "created_at": "2024-01-01T12:00:00.000000Z",
     *         "updated_at": "2024-01-01T12:00:00.000000Z"
     *     }
     * }
     * 
     * @response status=404 scenario="Not Found" {
     *     "status": false,
     *     "message": "العميل غير موجود",
     *     "data": null
     * }
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
     * Delete client
     * 
     * Remove the specified client from the system.
     *
     * @urlParam id required The ID of the client. Example: 1
     *
     * @response status=200 scenario="Success" {
     *     "status": true,
     *     "message": "تم حذف العميل بنجاح",
     *     "data": null
     * }
     * 
     * @response status=404 scenario="Not Found" {
     *     "status": false,
     *     "message": "العميل غير موجود",
     *     "data": null
     * }
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
