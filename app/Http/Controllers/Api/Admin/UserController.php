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
     */
    public function index()
    {

        $users = User::all();
        return apiResponse(UserResource::collection($users), 'تم جلب المستخدمين بنجاح');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $username = $validated['username'] ?? Str::slug($validated['name']);
        while (User::where('username', $username)->exists()) {
            $username = $username . '-' . Str::random(3);
        }
        $validated['username'] = $username;
        $user = User::create($validated);
        return apiResponse(new UserResource($user), 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return apiResponse(null, 'المستخدم غير موجود', 404);
        }
        return apiResponse(new UserResource($user), 'تم جلب المستخدم بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return apiResponse(null, 'تم حذف المستخدم بنجاح');
    }
}
