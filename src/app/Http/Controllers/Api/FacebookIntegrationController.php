<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Integrations\Facebook\FacebookOAuthService;
use Illuminate\Http\Request;

class FacebookIntegrationController extends Controller
{
    public function redirect(FacebookOAuthService $service)
    {
        return redirect(
            $service->getAuthorizationUrl()
        );
    }

    public function callback(
        Request $request,
        FacebookOAuthService $service
    ) {
        $service->handleCallback(
            tenantId: app('tenant')->id,
            code: $request->string('code')
        );

        return response()->json([
            'message' => 'Facebook connected successfully',
        ]);
    }
}