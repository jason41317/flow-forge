<?php

namespace App\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface OperatorContract
{
    public function apply(
        Builder $query,
        string $field,
        mixed $value
    ): Builder;
}
