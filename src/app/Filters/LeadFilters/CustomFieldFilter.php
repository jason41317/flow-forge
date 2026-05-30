<?php

namespace App\Filters\LeadFilters;

use App\Filters\OperatorResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomFieldFilter
{
    public function apply(
        Builder $query,
        array $fields
    ): Builder {

        if (! $fields) {
            return $query;
        }

        foreach ($fields as $fieldKey => $operators) {
            // Log::info($request->custom_fields);
            // Log::info($fieldKey);
            // Log::info($operators);
            foreach ($operators as $operator => $value) {
                $this->applyCustomFieldScope($query, $fieldKey, $operator, $value);
                // app(OperatorResolver::class)
                //     ->resolve($operator)
                //     ->apply(
                //         $query,
                //         $fieldKey,
                        
                //     );
            }
        }

        return $query;
    }

    private function applyCustomFieldScope(Builder $query, string $fieldKey, string $operator, mixed $value)
    {
        return $query->whereHas('fieldValues', function ($sub) use ($fieldKey, $value, $operator) {

            $sub->whereHas('field', function ($f) use ($fieldKey) {
                $f->where('key', $fieldKey);
            });

            // match ($operator) {
            //     'eq' => $sub->where('value', $value),
            //     'contains' => $sub->where('value', 'like', "%{$value}%"),
            //     'gt' => $sub->where('value', '>', $value),
            //     'lt' => $sub->where('value', '<', $value),
            //     'between' => $sub->whereBetween('value', $value),
            // };

            $resolvedOperator = app(
                OperatorResolver::class
            )->resolve($operator);

            if ($resolvedOperator) {
                $resolvedOperator->apply(
                    $sub,
                    'value',
                    $value
                );
            }
        });
    }
}
