<?php

namespace App\Services\Integrations\Facebook;

use App\Models\FacebookForm;

class FacebookLeadMapper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function map(
        FacebookForm $form,
        array $facebookFields
    ): array {

        $lead = [];

        $customFields = [];

        foreach ($form->mappings as $mapping) {

            $value = $facebookFields[
                $mapping->source_field
            ] ?? null;

            if ($mapping->target_type === 'lead') {
                $lead[$mapping->target_value] = $value;
            }

            if ($mapping->target_type === 'custom_field') {
                $customFields[
                    $mapping->target_value
                ] = $value;
            }
        }

        return [
            'lead' => $lead,
            'custom_fields' => $customFields,
        ];
    }
}
