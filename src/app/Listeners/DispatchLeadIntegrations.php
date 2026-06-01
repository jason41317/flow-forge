<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Services\Integrations\IntegrationDispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
