<?php

namespace App\Services\Integrations;

use App\Jobs\Integrations\GoogleSheetsJob;
use App\Models\Integration;
use App\Models\Lead;

class IntegrationDispatcher
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function dispatch(Lead $lead): void
    {
        $integrations = Integration::query()
            ->where('tenant_id', $lead->tenant_id)
            ->where('enabled', true)
            ->with('provider')
            ->get();

        foreach ($integrations as $integration) {

            $this->dispatchToProvider(
                $integration,
                $lead
            );
        }
    }

    private function dispatchToProvider(
        Integration $integration,
        Lead $lead
    ): void {
        $provider = $integration->provider->code;

        match ($provider) {

            'google_sheets' =>
                GoogleSheetsJob::dispatch($lead, $integration),

            // 'facebook_leads' =>
            //     FacebookLeadJob::dispatch($lead, $integration),

            default =>
                null,
        };
    }

}
