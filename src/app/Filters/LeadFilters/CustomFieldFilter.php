<?php

namespace App\Filters\LeadFilters;

use App\Contracts\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CustomFieldFilter implements FilterContract
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function apply(Builder $query, Request $request, string $key): Builder
    {
        foreach ($request->custom_fields as $fieldKey => $value) {

            $query->whereHas('fieldValues', function ($q) use ($fieldKey, $value) {

                $q->where('value', $value)
                    ->whereHas('field', function ($fieldQuery) use ($fieldKey) {

                        $fieldQuery->where('key', $fieldKey);

                    });

            });
        }

        return $query;

    }
}
