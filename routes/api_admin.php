<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UserController::class);
});
