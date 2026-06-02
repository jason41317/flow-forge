<?php

namespace App\Jobs\Integrations;

use App\Actions\CreateLeadAction;
use App\DTOs\LeadData;
use App\Models\Integration;
use App\Services\Integrations\Facebook\FacebookApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportFacebookLeadJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $leadgenId,
        public string $pageId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        FacebookApiService $facebook
    ): void {

       $integration = Integration::providerFilter('facebook')
            ->get()
            ->first(fn ($integration) =>
                $integration->getConfig('page_id') === $this->pageId
            );

        if (! $integration) {
            return;
        }

        $lead = $facebook->getLead(
            $this->leadgenId,
            $integration->config['access_token']
        );

        $fields = collect(
            $lead['field_data'] ?? []
        );

        $customFields = [];

        foreach ($fields as $field) {

            $name = $field['name'];

            $value = $field['values'][0] ?? null;

            $customFields[$name] = $value;
        }

        CreateLeadAction::run(
            new LeadData(
                // tenantId: $integration->tenant_id,

                firstName: $customFields['first_name'] ?? '',
                lastName: $customFields['last_name'] ?? '',
                email: $customFields['email'] ?? null,
                phone: $customFields['phone_number'] ?? null,

                source: 'facebook',
                type: 'lead',

                utmSource: null,
                utmMedium: null,
                utmCampaign: null,
                utmTerm: null,
                utmContent: null,

                customFields: $customFields,
            )
        );
    }

    public function failed(\Throwable $exception): void
    {
        logger()->error('Facebook lead import failed', [
            'leadgen_id' => $this->leadgenId,
            'page_id' => $this->pageId,
            'error' => $exception->getMessage(),
        ]);
    }
}
