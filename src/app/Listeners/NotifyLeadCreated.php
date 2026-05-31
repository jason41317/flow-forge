<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyLeadCreated
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
        // future:
        // - email notification
        // - Slack webhook
        // - CRM sync
    }
}
