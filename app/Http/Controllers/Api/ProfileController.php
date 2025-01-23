<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UpdateProfileInfo;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Update basic profile info
     * @param UpdateProfileInfo $request 
     * @return JasonResponse
     **/

    public function updateBasicProfileInfo(UpdateProfileInfo $request)
    {
        try {
            $user = Auth::user();
            $user->update($request->validated());
            return $this->success(new UserResource($user), 'تم تحديث المعلومات الشخصية بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
