<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{

        /**
     * Render the exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        return $this->handleApiException($e, $request);
    }

    /**
     * Handle API exceptions
     */
    private function handleApiException(Throwable $e, Request $request)
    {
        return match(true) {
            // Authentication Exceptions
            $e instanceof AuthenticationException,
            $e instanceof TokenInvalidException,
            $e instanceof TokenExpiredException => $this->error('Unauthenticated', 401),

            // Authorization Exception
            $e instanceof AuthorizationException => $this->error('Unauthorized', 403),

            // Validation Exception
            $e instanceof ValidationException => $this->error(
                'Validation Error', 
                422, 
                $e->errors()
            ),

            // Not Found Exceptions
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => $this->error(
                'Resource not found', 
                404
            ),

            // Method Not Allowed
            $e instanceof MethodNotAllowedHttpException => $this->error(
                'Method not allowed',
                405,
                null,
                ['allowed_methods' => $e->getHeaders()['Allow'] ?? null]
            ),

            // Rate Limiting
            $e instanceof ThrottleRequestsException => $this->error(
                'Too many requests',
                429
            ),

            // Default/Unknown Errors
            default => $this->error(
                $e->getMessage(),
                500,
                config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            )
        };
    }

    /**
     * Format error response
     */
    private function error(string $message, int $code = 400, ?array $errors = null, array $additional = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json(array_merge($response, $additional), $code);
    }
}
