<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Update2FARequest;
use App\Http\Requests\Api\UpdatePasswordRequest;

class SecurityController extends Controller
{
    /**
     * method for password change
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            $user->update([
                'password' => $request->password,
            ]);

            return $this->success(null, 'تم تعديل كلمة المرور بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * method for updating  [endable and disable, addign email and phone]
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update2FA(Update2FARequest $request)
    {
        try {
            $user = Auth::user();
            $user->update($request->safe()->all());
            return $this->success(null, 'تم تعديل البيانات بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
