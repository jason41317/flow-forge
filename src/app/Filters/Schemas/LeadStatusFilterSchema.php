<?php

namespace App\Filters\Schemas;

use App\Filters\Contracts\SchemaContract;

class LeadStatusFilterSchema implements SchemaContract
{
     public static function fields(): array
    {
        return [
            'id' => [
                'type' => 'int',
                'operators' => ['eq', 'gt', 'lt', 'between'],
            ],

            'name' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'color' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'is_default' => [
                'type' => 'boolean',
                'operators' => ['eq'],
            ],

            'is_closed' => [
                'type' => 'boolean',
                'operators' => ['eq'],
            ],

            'created_at' => [
                'type' => 'date',
                'operators' => ['eq', 'gt', 'lt', 'between'],
            ],
        ];
    }
}
