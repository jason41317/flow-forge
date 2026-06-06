<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Integrations\Facebook\FacebookOAuthService;
use App\Support\Tenant\TenantManager;
use Illuminate\Http\Request;

class FacebookIntegrationController extends Controller
{
    public function redirect(FacebookOAuthService $service)
    {
        return $service->getAuthorizationUrl();
    }

    public function callback(
        Request $request,
        FacebookOAuthService $service
    ) {
        // $tenant = app(TenantManager::class)->get();
        $service->handleCallback(
            // tenantId: $tenant->id,
            code: $request->string('code')
        );

        return response()->json([
            'message' => 'Facebook connected successfully',
        ]);
    }
}
