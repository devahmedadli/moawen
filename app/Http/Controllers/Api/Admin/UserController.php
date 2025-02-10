<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Admin\UserStoreRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        try {
            $query = User::query();
            $query = $this->buildQuery($request, $query);
            $users = $query->paginate($request->get('per_page', 25));
            return $this->success(
                UserResource::collection($users),
                'تم جلب المستخدمين بنجاح'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);
            $username = $validated['username'] ?? Str::slug($validated['name']);
            while (User::where('username', $username)->exists()) {
                $username = $username . '-' . Str::random(3);
            }
            $validated['username'] = $username;
            $user = User::create($validated);
            return $this->success(new UserResource($user), 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->success(new UserResource($user), 'تم جلب المستخدم بنجاح');
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
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'password'      => 'nullable|string|min:8',
        ]);
        try {
            $user = User::findOrFail($id);
            $user->update($validated);
            return $this->success(new UserResource($user), 'تم تحديث المستخدم بنجاح');
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
            $user = User::findOrFail($id);
            $user->delete();
            return $this->success(null, 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
