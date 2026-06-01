<?php

namespace App\Filters;

use App\Filters\LeadFilters\CustomFieldFilter;
use App\Filters\LeadFilters\SortFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LeadFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        // 1. MAIN FIELD OPERATORS
        if ($request->filled('filters')) {
            app(OperatorEngine::class)
                ->apply($query, $request->filters);
        }

        // 2. CUSTOM FIELDS
        if ($request->filled('custom_fields')) {
            app(CustomFieldFilter::class)
                ->apply($query, $request->custom_fields);
        }

        // 3. SORTING
        if ($request->filled('sort')) {
            app(SortFilter::class)
                ->apply($query, $request->sort);
        }

        return $query;
    }
}
