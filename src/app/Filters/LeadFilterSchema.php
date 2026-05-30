<?php

namespace App\Filters;

class LeadFilterSchema
{
    public static function fields(): array
    {
        return [
            'id' => [
                'type' => 'int',
                'operators' => ['eq', 'gt', 'lt', 'between'],
            ],

            'first_name' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'last_name' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'email' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'phone' => [
                'type' => 'string',
                'operators' => ['eq', 'contains'],
            ],

            'source' => [
                'type' => 'string',
                'operators' => ['eq'],
            ],

            'type' => [
                'type' => 'string',
                'operators' => ['eq'],
            ],

            'status' => [
                'type' => 'string',
                'operators' => ['eq'],
            ],

            'created_at' => [
                'type' => 'date',
                'operators' => ['eq', 'gt', 'lt', 'between'],
            ],
        ];
    }
}