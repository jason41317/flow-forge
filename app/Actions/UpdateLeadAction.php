<?php

namespace App\Actions;

use App\DTOs\LeadData;
use App\Events\LeadUpdated;
use App\Models\Lead;
use App\Models\LeadField;

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

        foreach ($data->customFields as $key => $value) {
            $field = LeadField::where('tenant_id', $lead->tenant_id)
                ->where('key', $key)
                ->first();

            if (! $field) {
                continue;
            }

            $lead->fieldValues()
                ->where('tenant_id', $lead->tenant_id)
                ->update([
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
