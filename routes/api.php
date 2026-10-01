<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ActivityController;
use App\Http\Controllers\Api\V1\HealthController;

Route::prefix('v1')->group(function () {
    Route::get('/health', [HealthController::class, 'index']);

    Route::middleware(['auth:sanctum', 'ability:activities:read', 'throttle:api'])
        ->get('/activities', [ActivityController::class, 'index']);
});