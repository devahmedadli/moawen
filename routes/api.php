<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserController;



Route::apiResource('freelancers', \App\Http\Controllers\Api\Admin\FreelancerController::class);
Route::apiResource('clients', \App\Http\Controllers\Api\Admin\ClientController::class);
Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class);
Route::apiResource('specializations', \App\Http\Controllers\Api\Admin\SpecializationController::class);
Route::apiResource('services', \App\Http\Controllers\Api\Admin\ServiceController::class);
Route::apiResource('orders', \App\Http\Controllers\Api\Admin\OrderController::class);
Route::apiResource('reviews', \App\Http\Controllers\Api\Admin\ReviewController::class);
Route::apiResource('posts', \App\Http\Controllers\Api\Admin\PostController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UserController::class);
});




