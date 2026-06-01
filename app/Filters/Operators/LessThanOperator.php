<?php

namespace App\Filters\Operators;

use App\Filters\Contracts\OperatorContract;
use Illuminate\Database\Eloquent\Builder;

class LessThanOperator implements OperatorContract
{
    public function apply(
        Builder $query,
        string $field,
        mixed $value
    ): Builder {

        return $query->where(
            $field,
            '<',
            $value
        );
    }
}
