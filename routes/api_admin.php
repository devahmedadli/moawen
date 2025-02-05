<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('verify.token')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UserController::class);
});