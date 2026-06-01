<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Services\Integrations\IntegrationDispatcher;

class DispatchLeadIntegrations
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
        app(IntegrationDispatcher::class)
            ->dispatch($event->lead);
    }
}
