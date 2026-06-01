<?php

namespace App\Listeners;

use App\Actions\CreateAuditLogAction;
use App\Events\LeadUpdated;
use Illuminate\Support\Facades\Auth;

class LogLeadUpdated
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
    public function handle(LeadUpdated $event): void
    {
        CreateAuditLogAction::run(
            tenantId: $event->lead->tenant_id,
            userId: Auth::id(),
            event: 'updated',
            entityType: 'lead',
            entityId: $event->lead->id,
            oldValues: $event->oldValues,
            newValues: $event->lead->toArray(),
        );
    }
}
