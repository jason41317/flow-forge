<?php

namespace App\Filters\Operators;

use App\Filters\Contracts\OperatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class BetweenOperator implements OperatorContract
{
    public function apply(
        Builder $query,
        string $field,
        mixed $value
    ): Builder {
        if (!is_array($value) || count($value) !== 2) {
            return $query;
        }

        return $query->whereBetween($field, $value);
    }
}