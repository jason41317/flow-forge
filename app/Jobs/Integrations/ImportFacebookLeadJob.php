<?php

namespace App\Jobs\Integrations;

use App\Actions\CreateLeadAction;
use App\DTOs\LeadData;
use App\Models\FacebookForm;
use App\Models\Integration;
use App\Services\Integrations\Facebook\FacebookApiService;
use App\Services\Integrations\Facebook\FacebookLeadMapper;
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
        public string $pageId,
        public string $formId,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        FacebookApiService $facebook,
        FacebookLeadMapper $mapper,
    ): void {

       $integration = Integration::providerFilter('facebook')
            ->get()
            ->first(fn ($integration) =>
                $integration->getConfig('page_id') === $this->pageId
            );

        if (! $integration) {
            return;
        }

        $form = FacebookForm::with('mappings')
            ->where(
                'facebook_form_id',
                $this->formId
            )
            ->first();

        if (! $form) {
            return;
        }

        $lead = $facebook->getLead(
            $this->leadgenId,
            $integration->config['access_token']
        );

        $customFields = [];

        $mapped = $mapper->map(
            $form,
            $lead['field_data'] ?? []
        );

        CreateLeadAction::run(
            new LeadData(
                // tenantId: $integration->tenant_id,

                firstName: $mapped['lead']['first_name'] ?? '',
                lastName: $mapped['lead']['last_name'] ?? '',
                email: $mapped['lead']['email'] ?? null,
                phone: $mapped['lead']['phone'] ?? null,

                customFields: $mapped['custom_fields'],

                source: 'facebook',
                type: 'lead',

                utmSource: null,
                utmMedium: null,
                utmCampaign: null,
                utmTerm: null,
                utmContent: null,
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
