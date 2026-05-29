<?php

namespace App\Filters\LeadFilters;

use App\Contracts\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SortFilter implements FilterContract
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
        $sort = $request->$key;

        $direction = str_starts_with($sort, '-')
            ? 'desc'
            : 'asc';

        $column = ltrim($sort, '-');

        $allowedSorts = [
            'created_at',
            'first_name',
            'email',
        ];

        if (in_array($column, $allowedSorts)) {
            return $query->orderBy($column, $direction);
        }

        return $query;
    }
}
