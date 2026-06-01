<?php

namespace App\Listeners;

use App\Actions\CreateAuditLogAction;
use App\Events\LeadCreated;
use Illuminate\Support\Facades\Auth;

class LogLeadCreated
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
        CreateAuditLogAction::run(
            tenantId: $event->lead->tenant_id,
            userId: Auth::id(),
            event: 'created',
            entityType: 'lead',
            entityId: $event->lead->id,
            oldValues: null,
            newValues: $event->lead->toArray(),
        );
    }
}
