<?php

namespace App\Actions\Facebook;

use App\Models\Integration;
use App\Models\IntegrationProvider;
use Illuminate\Support\Facades\Http;

class SyncFacebookPagesAction
{
    public static function run(
        int $tenantId,
        string $accessToken
    ): void {

        $provider = IntegrationProvider::where(
            'code',
            'facebook'
        )->firstOrFail();

        $response = Http::get(
            'https://graph.facebook.com/v20.0/me/accounts',
            [
                'access_token' => $accessToken,
            ]
        );

        foreach ($response->json('data', []) as $page) {

            Integration::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'integration_provider_id' => $provider->id,
                    'name' => $page['name'],
                ],
                [
                    'enabled' => true,
                    'config' => [
                        'page_id' => $page['id'],
                        'page_name' => $page['name'],
                        'access_token' => $page['access_token'],
                    ],
                ]
            );
        }
    }
}
