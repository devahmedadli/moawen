<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterUserRequest;

class AuthController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            $token = Auth::attempt($credentials);
            if (!$token) {
                return $this->error(
                    'بيانات دخول غير صحيحة',
                    401
                );
            }
            $user = Auth::user();
            $user->token = $token;
            return $this->success([
                [
                    'user'  => new UserResource($user),
                    'token' => $token
                ],
            ], 'تم تسجيل الدخول بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Register a new user.
     *
     * @param \App\Http\Requests\Api\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create(
                [
                    ...$request->validated(),
                    'username' => '',
                    ]
                );
                // dd($request->validated());
            if ($user) {
                // login user
                $token = Auth::login($user);
                $user->token = $token;
                return $this->success([
                    'user'  => new UserResource($user),
                    'token' => $token
                ], 'تم إنشاء المستخدم بنجاح');
            }
            return $this->error('حدث خطأ ما', 500);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        try {

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            // check if token is valid
            if (!JWTAuth::setToken($token)->check()) {
                return $this->error('الرمز غير صالح', 401);
            }
            $user = JWTAuth::setToken($token)->toUser();
            return $this->success(new UserResource($user), 'تم جلب المستخدم بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->header('Authorization'));
            return $this->success([], 'تم تسجيل الخروج بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $newToken = JWTAuth::setToken($token)->refresh();
            // remove old token
            JWTAuth::invalidate($token);
            return $this->success([
                'token' => $newToken
            ], 'تم تجديد الرمز بنجاح');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
