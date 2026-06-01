<?php

namespace App\Filters;

use App\Filters\Operators\BetweenOperator;
use App\Filters\Operators\ContainsOperator;
use App\Filters\Operators\EqualsOperator;
use App\Filters\Operators\GreaterThanOperator;
use App\Filters\Operators\LessThanOperator;

class OperatorResolver
{
    public function resolve(string $operator)
    {
        return match ($operator) {
            'eq' => app(EqualsOperator::class),
            'contains' => app(ContainsOperator::class),
            'gt' => app(GreaterThanOperator::class),
            'lt' => app(LessThanOperator::class),
            'between' => app(BetweenOperator::class),
            default => null,
        };
    }
}
