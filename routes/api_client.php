<?php

use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['user.client','verify.token'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Api\Client\DashboardController::class);
    Route::prefix('security')->group(function () {
        Route::put('/update-password', [\App\Http\Controllers\Api\SecurityController::class, 'updatePassword']);
        Route::put('/update-2fa', [\App\Http\Controllers\Api\SecurityController::class, 'update2FA']);
    });
    Route::put('/update-profile', [\App\Http\Controllers\Api\ProfileController::class, 'updateBasicProfileInfo']);
    Route::apiResource('/purchases', \App\Http\Controllers\Api\Client\PurchaseController::class);
    Route::apiResource('/purchases/reviews', \App\Http\Controllers\Api\Client\ReviewController::class)->except(['index', 'show']);
    Route::post('/offers/{id}/accept', [\App\Http\Controllers\Api\Client\OfferController::class, 'acceptOffer']);
    Route::post('/offers/{id}/reject', [\App\Http\Controllers\Api\Client\OfferController::class, 'rejectOffer']);

    Route::post('/chats/start-chat', [\App\Http\Controllers\MessageController::class, 'firstMessage']);
    Route::post('/chats/messages', [\App\Http\Controllers\MessageController::class, 'store']);
    Route::apiResource('/chats', \App\Http\Controllers\Api\Client\ChatController::class);

    // Payments

    Route::post('charges/create-charge', [PaymentController::class, 'createCharge']);
});
