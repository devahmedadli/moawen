<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['user.freelancer','verify.token'])->group(function () {

    Route::get('/dashboard', \App\Http\Controllers\Api\Freelancer\DashboardController::class);
    Route::prefix('security')->group(function () {
        Route::put('/update-password', [\App\Http\Controllers\Api\SecurityController::class, 'updatePassword']);
        Route::put('/update-2fa', [\App\Http\Controllers\Api\SecurityController::class, 'update2FA']);
    });
    Route::put('/update-profile', [\App\Http\Controllers\Api\ProfileController::class, 'updateBasicProfileInfo']);
    Route::apiResource('/services', \App\Http\Controllers\Api\Freelancer\ServiceController::class);
    Route::apiResource('/portfolios', \App\Http\Controllers\Api\Freelancer\PortfolioController::class);
    Route::delete('/portfolio-images/{id}', [\App\Http\Controllers\Api\Freelancer\PortfolioImageController::class, 'destroy']);
    Route::apiResource('/orders', \App\Http\Controllers\Api\Freelancer\OrderController::class);
    Route::apiResource('/offers', \App\Http\Controllers\Api\Freelancer\OfferController::class);
});
