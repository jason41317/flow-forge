<?php

namespace App\Services\Integrations;

use App\Services\Integrations\Providers\FacebookProvider;
use App\Services\Integrations\Providers\GoogleSheetsProvider;

class IntegrationProviderResolver
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function resolve(string $code): mixed
    {
        return match ($code) {

            'google_sheets' => app(GoogleSheetsProvider::class),

            'facebook_leads' => app(FacebookProvider::class),

            default => throw new \Exception("Unsupported provider: {$code}"),
        };
    }
}
