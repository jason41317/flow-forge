<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacebookFormController;
use App\Http\Controllers\Api\FacebookIntegrationController;
use App\Http\Controllers\Api\IntegrationController;
use App\Http\Controllers\Api\IntegrationProviderController;
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


        Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
            Route::get('/me', [AuthController::class, 'me']);

            Route::prefix('integrations/facebook')
            ->group(function () {
                Route::get('/connect', [FacebookIntegrationController::class, 'redirect']);
                Route::get('/callback', [FacebookIntegrationController::class, 'callback']);
            });

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

            // Facebook
            Route::get('/integration-providers', [IntegrationProviderController::class, 'index']);
            Route::get('/integrations', [IntegrationController::class, 'index']);
            Route::get('/facebook/forms', [FacebookFormController::class, 'index']);
            Route::get('/facebook/forms/{id}', [FacebookFormController::class, 'show']);
            Route::get('/facebook/forms/{id}/mappings', [FacebookFormController::class, 'mappings']);
            Route::put('/facebook/forms/{id}/mappings', [FacebookFormController::class, 'updateMappings']);
            Route::post('/facebook/integrations/{integrationId}/sync', [FacebookFormController::class, 'sync']);
        });
    });
