<?php

namespace App\Services\Integrations\Facebook;

use App\Actions\CreateLeadAction;
use App\DTOs\LeadData;

class FacebookLeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(array $payload): void
    {
        $leadgenId = data_get($payload, 'entry.0.changes.0.value.leadgen_id');

        if (! $leadgenId) {
            return;
        }

        $leadData = $this->fetchLeadFromFacebook($leadgenId);

        CreateLeadAction::run(
            new LeadData(
                firstName: $leadData['first_name'],
                lastName: $leadData['last_name'],
                email: $leadData['email'] ?? null,
                phone: $leadData['phone'] ?? null,
                source: 'facebook',
                type: 'facebook',
                utmSource: null,
                utmMedium: null,
                utmCampaign: null,
                utmTerm: null,
                utmContent: null,
                customFields: [],
                // tenantId: $this->resolveTenant($payload),
            )
        );
    }

    private function fetchLeadFromFacebook(string $leadgenId): array
    {
        // Graph API call (we will implement next)
        return [];
    }

    private function resolveTenant(array $payload): int
    {
        // map page_id → tenant
        return 1;
    }
}
