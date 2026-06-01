<?php

namespace App\Actions;

use App\DTOs\LeadData;
use App\Events\LeadUpdated;
use App\Models\Lead;
use App\Models\LeadField;
use App\Models\LeadFieldValue;

class UpdateLeadAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function run(Lead $lead, LeadData $data)
    {
        $oldValues = $lead->getOriginal();

        $lead->update([
            'first_name' => $data->firstName,
            'last_name' => $data->lastName,
            'email' => $data->email,
            'phone' => $data->phone,

            'source' => $data->source,
            'type' => $data->type,
        ]);

        // dynamic fields (unchanged)
        foreach ($data->customFields as $key => $value) {
            $field = LeadField::where('tenant_id', $data->tenantId)
                ->where('key', $key)
                ->first();

            if (! $field) {
                continue;
            }

            LeadFieldValue::create([
                'tenant_id' => $data->tenantId,
                'lead_id' => $lead->id,
                'lead_field_id' => $field->id,
                'value' => $value,
            ]);
        }

        event(new LeadUpdated(
            $lead->fresh(),
            $oldValues
        ));

        return $lead;
    }
}
