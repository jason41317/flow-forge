<?php

namespace App\Filters\LeadFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SortFilter
{
    public function apply(Builder $query, string $sort): Builder
    {
        if (! $sort) {
            return $query;
        }

        $direction = str_starts_with($sort, '-')
            ? 'desc'
            : 'asc';

        $column = ltrim($sort, '-');

        $allowed = [
            'created_at',
            'first_name',
            'email',
            'updated_at',
            'id'
        ];

        if (! in_array($column, $allowed)) {
            return $query;
        }

        return $query->orderBy($column, $direction);
    }
}
