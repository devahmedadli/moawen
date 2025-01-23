<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class VerifyTokenIsValid
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            if ($token == null) {
                return $this->error('غير مصرح بالدخول', 401);
            }
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
        return $next($request);
    }
}
