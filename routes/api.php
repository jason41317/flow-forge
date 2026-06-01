<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacebookIntegrationController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LeadStatusController;
use App\Http\Controllers\Webhooks\FacebookWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        // Facebook Webhook
        Route::post('/webhooks/facebook/lead',
            [FacebookWebhookController::class, 'handle']
        );

        Route::prefix('integrations/facebook')
            ->group(function () {
                Route::get('/connect', [FacebookIntegrationController::class, 'redirect']);
                Route::get('/callback', [FacebookIntegrationController::class, 'callback']);
            });

        Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
            Route::get('/me', fn () => request()->user());

            // Leads
            Route::get('/leads', [LeadController::class, 'index']);
            Route::post('/leads', [LeadController::class, 'store']);
            Route::put('/leads/{lead}', [LeadController::class, 'update']);
            Route::get('/leads/{lead}', [LeadController::class, 'show']);

            // Lead Status
            Route::get('/lead-statuses', [LeadStatusController::class, 'index']);
            Route::post('/lead-statuses', [LeadStatusController::class, 'store']);
            Route::put('/lead-statuses/{id}', [LeadStatusController::class, 'update']);
            Route::get('/lead-statuses/{id}', [LeadStatusController::class, 'show']);
            Route::delete('/lead-statuses/{id}', [LeadStatusController::class, 'destroy']);
        });
    });
