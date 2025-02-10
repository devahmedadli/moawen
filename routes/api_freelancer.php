<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['user.freelancer','verify.token'])->group(function () {

    Route::get('/dashboard', \App\Http\Controllers\Api\Freelancer\DashboardController::class);
    // Account settings
    Route::prefix('security')->group(function () {
        Route::put('/update-password', [\App\Http\Controllers\Api\SecurityController::class, 'updatePassword']);
        Route::put('/update-2fa', [\App\Http\Controllers\Api\SecurityController::class, 'update2FA']);
    });
    
    // Profile settings
    Route::put('/update-profile', [\App\Http\Controllers\Api\ProfileController::class, 'updateBasicProfileInfo']);

    Route::apiResource('/services', \App\Http\Controllers\Api\Freelancer\ServiceController::class);

    Route::apiResource('/portfolios', \App\Http\Controllers\Api\Freelancer\PortfolioController::class);
    Route::delete('/portfolio-images/{id}', [\App\Http\Controllers\Api\Freelancer\PortfolioImageController::class, 'destroy']);

    Route::apiResource('/orders', \App\Http\Controllers\Api\Freelancer\OrderController::class);

    Route::apiResource('/offers', \App\Http\Controllers\Api\Freelancer\OfferController::class);

    // Chat
    Route::post('/chats/start-chat', [\App\Http\Controllers\MessageController::class, 'firstMessage']);
    Route::post('/chats/messages', [\App\Http\Controllers\MessageController::class, 'store']);
    Route::apiResource('/chats', \App\Http\Controllers\Api\Freelancer\ChatController::class);

    // Withdrawals
    Route::apiResource('withdrawals', \App\Http\Controllers\Api\Freelancer\WithdrawalController::class)->only(['index', 'store']);
    Route::put('withdrawals/{withdrawal}/cancel', [\App\Http\Controllers\Api\Freelancer\WithdrawalController::class, 'cancel']);

    // Payments
    Route::post('charges/create-charge', [\App\Http\Controllers\Api\PaymentController::class, 'createCharge']);

});
