<?php

namespace App\Services\Integrations\Providers;

use App\Jobs\Integrations\ImportFacebookLeadJob;
use App\Models\Integration;
use App\Models\Lead;

class FacebookProvider
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
        ImportFacebookLeadJob::dispatch($lead, $integration);
    }
}
