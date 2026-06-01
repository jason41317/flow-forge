<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OperatorEngine
{
    protected array $allowedFields = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'type',
        'status',
        'created_at',
        'updated_at',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    public function __construct(
        protected OperatorResolver $operatorResolver,
        protected SchemaResolver $schemaResolver
    ) {}

    public function apply(Builder $query, array $filters): Builder
    {
        $schema = $this->schemaResolver
            ->resolve(get_class($query->getModel()))
            ->fields();

        foreach ($filters as $field => $operators) {

            if (! isset($schema[$field])) {
                continue;
            }

            foreach ($operators as $operator => $value) {
                // Log::info($value);

                if (! in_array($operator, $schema[$field]['operators'])) {
                    throw ValidationException::withMessages([
                        "filters.$field" => "Operator [$operator] not allowed for field [$field].",
                    ]);
                }

                $value = $this->castValue($value, $operator == 'between' ? 'array' : $schema[$field]['type']);

                // Log::info('Value after cast - ' . $value);

                $this->operatorResolver
                    ->resolve($operator)
                    ->apply($query, $field, $value);
            }
        }

        return $query;
    }

    private function castValue(mixed $value, string $type)
    {
        return match ($type) {
            'int' => (int) $value,
            'string' => (string) $value,
            'date' => $value,
            'array' => json_decode($value, true),
            default => $value,
        };
    }
}
