<?php

namespace App\Filters\LeadFilters;

use App\Contracts\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TypeFilter implements FilterContract
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function apply(Builder $query, Request $request, string $key): Builder
    {
        return $query->where('type', $request->$key);
    }

}
