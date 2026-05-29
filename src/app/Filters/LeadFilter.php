<?php

namespace App\Filters;

use App\Filters\LeadFilters\CustomFieldFilter;
use App\Filters\LeadFilters\SearchFilter;
use App\Filters\LeadFilters\SortFilter;
use App\Filters\LeadFilters\SourceFilter;
use App\Filters\LeadFilters\TypeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LeadFilter
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function apply(Builder $query, Request $request): Builder
    {
         $filters = [
            'type' => TypeFilter::class,
            'source' => SourceFilter::class,
            'search' => SearchFilter::class,
            'sort' => SortFilter::class,
            'custom_fields' => CustomFieldFilter::class,
        ];

        foreach ($filters as $key => $filterClass) {

            if (!$request->filled($key)) {
                continue;
            }

            app($filterClass)->apply(
                $query,
                $request,
                $key
            );
        }

        return $query;
    }

}
