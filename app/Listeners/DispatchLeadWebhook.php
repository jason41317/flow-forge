<?php

namespace App\Listeners;

use App\Events\LeadCreated;

class DispatchLeadWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadCreated $event): void
    {
        // placeholder SaaS integration hook

        $lead = $event->lead;

        // Example future integration
        // Http::post($tenantWebhookUrl, [...]);
    }
}
