<?php

namespace App\Filters\LeadFilters;

use App\Contracts\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchFilter implements FilterContract
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
        $value = $request->$key;
        return $query->where(function ($q) use ($value) {
            $q->where('first_name', 'like', "%{$value}%")
                ->orWhere('last_name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
        });
    }
}
