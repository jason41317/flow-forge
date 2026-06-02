<?php

namespace App\Services\Integrations\Facebook;

use Illuminate\Support\Facades\Http;

class FacebookApiService
{
    public function getLead(
        string $leadgenId,
        string $accessToken
    ): array {

        $response = Http::get(
            "https://graph.facebook.com/v23.0/{$leadgenId}",
            [
                'access_token' => $accessToken,
            ]
        );

        if (! $response->successful()) {
            throw new \Exception(
                $response->body()
            );
        }

        return $response->json();
    }
}
