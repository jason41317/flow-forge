<?php

namespace App\Actions;

use App\DTOs\LeadStatusData;
use App\Models\LeadStatus;

class CreateLeadStatusAction
{
    /**
     * Create a new class instance.
     */
    public static function run(LeadStatusData $data)
    {
        $leadStatus = LeadStatus::create([
            'tenant_id' => $data->tenantId,
            'name' => $data->name,
            'color' => $data->color,
            'is_default' => $data->isDefault,
            'is_closed' => $data->isClosed
        ]);

        // event

        return $leadStatus;
    }
}
