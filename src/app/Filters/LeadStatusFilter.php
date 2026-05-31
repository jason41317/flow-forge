<?php

namespace App\Filters;

use App\Filters\LeadFilters\SortFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LeadStatusFilter
{
    public function apply(Builder $query, Request $request): Builder
    {
        if ($request->filled('filters')) {
            app(OperatorEngine::class)
                ->apply($query, $request->filters);
        }

         if ($request->filled('sort')) {
            app(SortFilter::class)
                ->apply($query, $request->sort);
        }

        return $query;
    }
}
