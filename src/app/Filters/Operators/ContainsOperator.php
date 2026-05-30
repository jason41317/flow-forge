<?php

namespace App\Filters\Operators;

use Illuminate\Database\Eloquent\Builder;

class ContainsOperator
{
    public function apply(
        Builder $query,
        string $field,
        mixed $value
    ): Builder {
        return $query->where($field, 'like', "%{$value}%");
    }
}
