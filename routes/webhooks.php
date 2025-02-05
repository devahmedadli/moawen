<?php

use Illuminate\Support\Facades\Route;

Route::post('/webhooks/tap', [\App\Http\Controllers\Api\WebhookController::class, 'handleTapWebhook']);
