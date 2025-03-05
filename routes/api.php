<?php

use Illuminate\Support\Facades\Route;


// Public routes
Route::apiResource('freelancers', \App\Http\Controllers\Api\Admin\FreelancerController::class)->only(['index', 'show']);
Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class)->only(['index', 'show']);
Route::apiResource('specializations', \App\Http\Controllers\Api\Admin\SpecializationController::class)->only(['index', 'show']);
Route::apiResource('services', \App\Http\Controllers\Api\Admin\ServiceController::class)->only(['index', 'show']);
Route::apiResource('posts', \App\Http\Controllers\Api\Admin\PostController::class)->only(['index', 'show']);
Route::get('attachments/{attachment}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])
    ->name('attachments.download')
    ->middleware('verify.token');
// Protected admin routes
Route::middleware('user.admin', 'verify.token')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Api\Admin\DashboardController::class);
    Route::apiResource('freelancers', \App\Http\Controllers\Api\Admin\FreelancerController::class)->except(['index', 'show']);
    Route::apiResource('clients', \App\Http\Controllers\Api\Admin\ClientController::class);
    Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class)->except(['index', 'show']);
    Route::apiResource('specializations', \App\Http\Controllers\Api\Admin\SpecializationController::class)->except(['index', 'show']);
    Route::put('services/{id}/update-status', [\App\Http\Controllers\Api\Admin\ServiceController::class, 'updateStatus']);
    Route::apiResource('services', \App\Http\Controllers\Api\Admin\ServiceController::class)->except(['index', 'show']);
    Route::apiResource('orders', \App\Http\Controllers\Api\Admin\OrderController::class);
    Route::apiResource('reviews', \App\Http\Controllers\Api\Admin\ReviewController::class);
    Route::apiResource('posts', \App\Http\Controllers\Api\Admin\PostController::class)->except(['index', 'show']);
    Route::apiResource('withdrawals', \App\Http\Controllers\Api\Admin\WithdrawalController::class)->only(['index', 'show']);
    Route::put('withdrawals/{id}/update-status', [\App\Http\Controllers\Api\Admin\WithdrawalController::class, 'updateStatus']);
    Route::apiResource('transactions', \App\Http\Controllers\Api\Admin\TransactionController::class)->only(['index', 'show']);
});
