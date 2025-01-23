<?php

namespace App\Traits;

use PDOException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ApiResponse
{
    /**
     * Success Response
     */
    protected function success(
        mixed $data = null,
        string $message = null,
        int $code = 200,
        array $additional = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        // Handle pagination metadata if data is a ResourceCollection
        if ($data instanceof ResourceCollection && method_exists($data->resource, 'currentPage')) {
            $response['meta'] = [
                'pagination' => [
                    'total'         => $data->resource->total(),
                    'per_page'      => $data->resource->perPage(),
                    'current_page'  => $data->resource->currentPage(),
                    'last_page'     => $data->resource->lastPage(),
                    'from'          => $data->resource->firstItem(),
                    'to'            => $data->resource->lastItem(),
                ],
            ];

            $response['links'] = [
                'first'     => $data->resource->url(1),
                'last'      => $data->resource->url($data->resource->lastPage()),
                'prev'      => $data->resource->previousPageUrl(),
                'next'      => $data->resource->nextPageUrl(),
            ];
        }

        return response()->json(array_merge($response, $additional), $code);
    }

    /**
     * Error Response
     */
    protected function error(
        string $message,
        int $code = 400,
        mixed $errors = null,
        array $additional = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'data'  => null,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        // Add debug information in non-production environment
        if (config('app.debug')) {
            $response['debug'] = [
                'file' => debug_backtrace()[1]['file'] ?? null,
                'line' => debug_backtrace()[1]['line'] ?? null,
            ];
        }

        return response()->json(array_merge($response, $additional), $code);
    }

    /**
     * Paginated Response
     */
    protected function paginated(
        ResourceCollection $collection,
        string $message = null,
        int $code = 200,
        array $additional = []
    ): JsonResponse {
        return $this->success($collection, $message, $code, $additional);
    }

    /**
     * Validation Error Response
     */
    protected function validationError(
        array $errors,
        string $message = 'خطأ في البيانات',
        int $code = 422
    ): JsonResponse {
        return $this->error($message, $code, $errors);
    }

    /**
     * Not Found Response
     */
    protected function notFound(
        string $message = 'المورد غير موجود',
        array $additional = []
    ): JsonResponse {
        return $this->error($message, 404, null, $additional);
    }

    /**
     * Unauthorized Response
     */
    protected function unauthorized(
        string $message = 'ليس لديك الصلاحية للدخول',
        array $additional = []
    ): JsonResponse {
        return $this->error($message, 401, null, $additional);
    }

    /**
     * Forbidden Response
     */
    protected function forbidden(
        string $message = 'ليس لديك الصلاحية للدخول',
        array $additional = []
    ): JsonResponse {
        return $this->error($message, 403, null, $additional);
    }

    /**
     * Handle Exceptions
     */
    protected function handleException(\Exception $e)
    {
        $this->logError($e);

        return match (true) {
            $e instanceof ValidationException           => $this->error('البيانات غير صالحة', 422, $e->errors()),
            $e instanceof ModelNotFoundException        => $this->notFound('المورد غير موجود'),
            $e instanceof AuthenticationException       => $this->unauthorized('غير مصرح بالدخول'),
            $e instanceof AuthorizationException        => $this->forbidden('غير مصرح بالدخول'),
            $e instanceof MethodNotAllowedHttpException => $this->error('طريقة غير مسموح بها', 405),
            $e instanceof NotFoundHttpException         => $this->notFound('الرابط غير موجود'),
            $e instanceof QueryException                => $this->error('خطأ في القاعدة', 500),
            $e instanceof TokenMismatchException        => $this->error('تطابق CSRF الرمز', 419),
            $e instanceof ThrottleRequestsException     => $this->error('طلبات كثيرة', 429),
            $e instanceof PDOException                  => $this->error('خطأ في الاتصال بالقاعدة', 500),
            $e instanceof TokenInvalidException         => $this->unauthorized('الرمز غير صالح'),
            $e instanceof TokenExpiredException         => $this->unauthorized('الرمز منتهي الصلاحية'),
            default                                     => $this->error($e->getMessage(), 500)
        };
    }

    // function to log the error
    protected function logError(\Exception $e)
    {
        Log::error('Error: ' . $e->getMessage());
    }
}
