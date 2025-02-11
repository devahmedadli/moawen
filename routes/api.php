<?php

use Illuminate\Support\Facades\Route;


Route::get('/dashboard', \App\Http\Controllers\Api\Admin\DashboardController::class);
Route::apiResource('freelancers', \App\Http\Controllers\Api\Admin\FreelancerController::class);
Route::apiResource('clients', \App\Http\Controllers\Api\Admin\ClientController::class);
Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class);
Route::apiResource('specializations', \App\Http\Controllers\Api\Admin\SpecializationController::class);
Route::put('services/{id}/update-status', [\App\Http\Controllers\Api\Admin\ServiceController::class, 'updateStatus']);
Route::apiResource('services', \App\Http\Controllers\Api\Admin\ServiceController::class);
Route::apiResource('orders', \App\Http\Controllers\Api\Admin\OrderController::class);
Route::apiResource('reviews', \App\Http\Controllers\Api\Admin\ReviewController::class);
Route::apiResource('posts', \App\Http\Controllers\Api\Admin\PostController::class);
Route::apiResource('withdrawals', \App\Http\Controllers\Api\Admin\WithdrawalController::class)->only(['index', 'show']);
Route::put('withdrawals/{id}/update-status', [\App\Http\Controllers\Api\Admin\WithdrawalController::class, 'updateStatus']);
Route::apiResource('transactions', \App\Http\Controllers\Api\Admin\TransactionController::class)->only(['index', 'show']);
// Route::group(function () {
// });
