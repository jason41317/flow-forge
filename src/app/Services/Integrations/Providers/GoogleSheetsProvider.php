<?php

namespace App\Services\Integrations\Providers;

use App\Jobs\Integrations\GoogleSheetsJob;
use App\Models\Integration;
use App\Models\Lead;
use App\Services\Integrations\Contracts\IntegrationProviderInterface;

class GoogleSheetsProvider implements IntegrationProviderInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(Integration $integration, Lead $lead): void
    {
        GoogleSheetsJob::dispatch($lead, $integration);
    }

}
