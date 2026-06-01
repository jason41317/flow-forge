<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,

            'source' => $this->source,
            'type' => $this->type,
            'status' => $this->status,

            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,

            'custom_fields' => $this->formatCustomFields(),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function formatCustomFields(): array
    {
        return $this->fieldValues
            ->load('field')
            ->mapWithKeys(function ($value) {
                return [
                    $value->field->key => $value->value,
                ];
            })
            ->toArray();
    }
}
