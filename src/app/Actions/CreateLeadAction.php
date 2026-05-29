<?php

namespace App\Actions;

use App\DTOs\LeadData;
use App\Events\LeadCreated;
use App\Models\Lead;
use App\Models\LeadField;
use App\Models\LeadFieldValue;

class CreateLeadAction
{
    /**
     * Create a new class instance.
     */
    public static function run(LeadData $data)
    {
        $lead = Lead::create([
            'tenant_id' => $data->tenantId,

            'first_name' => $data->firstName,
            'last_name' => $data->lastName,
            'email' => $data->email,
            'phone' => $data->phone,

            'source' => $data->source,
            'type' => $data->type,

            'utm_source' => $data->utmSource,
            'utm_medium' => $data->utmMedium,
            'utm_campaign' => $data->utmCampaign,
            'utm_term' => $data->utmTerm,
            'utm_content' => $data->utmContent,

            'status' => 'new',
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

        event(new LeadCreated($lead));

        return $lead;
    }
}
