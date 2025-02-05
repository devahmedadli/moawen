<?php

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->prefix('api')->group(base_path('routes/auth.php')); // register auth routes
            Route::middleware('api')->prefix('api/freelancer')->group(base_path('routes/api_freelancer.php')); // register freelancer routes
            Route::middleware('api')->prefix('api/client')->group(base_path('routes/api_client.php')); // register user routes
            Route::middleware('api')->prefix('api/admin')->group(base_path('routes/api_admin.php')); // register admin routes
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'verify.token'      => App\Http\Middleware\VerifyTokenIsValid::class,
            'user.client'       => App\Http\Middleware\IsClient::class,
            'user.freelancer'   => App\Http\Middleware\IsFreelancer::class,
            'user.admin'        => App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (Handler $handler){
        //     return $handler->register();
        // });
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                dd($e);
                return response()->json([
                    'message' => 'Record not found.'
                ], 400);
            }
        });
    })->create();
