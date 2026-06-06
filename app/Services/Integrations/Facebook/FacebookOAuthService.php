<?php

namespace App\Services\Integrations\Facebook;

use App\Actions\Facebook\SyncFacebookPagesAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookOAuthService
{
    public function getAuthorizationUrl(): string
    {
        return 'https://www.facebook.com/v20.0/dialog/oauth?'.
            http_build_query([
                'client_id' => config('services.facebook.app_id'),
                'redirect_uri' => config('services.facebook.redirect_uri'),
                'scope' => implode(',', [
                    'pages_show_list',
                    'pages_read_engagement',
                    'leads_retrieval',
                ]),
                'response_type' => 'code',
            ]);
    }

    public function handleCallback(
        // int $tenantId,
        string $code
    ): void {

        $response = Http::withOptions([
            'verify' => false, // Disable SSL verification
        ])
        ->get(
            'https://graph.facebook.com/v20.0/oauth/access_token',
            [
                'client_id' => config('services.facebook.app_id'),
                'client_secret' => config('services.facebook.app_secret'),
                'redirect_uri' => config('services.facebook.redirect_uri'),
                'code' => $code,
            ]
        );

        Log::info('Facebook OAuth response', ['response' => $response->body()]);

        $token = $response->json('access_token');

        SyncFacebookPagesAction::run(
            // tenantId: $tenantId,
            accessToken: $token
        );
    }
}
