<?php

namespace App\Filters;

use App\Filters\Schemas\LeadFilterSchema;
use App\Filters\Schemas\LeadStatusFilterSchema;
use App\Models\Lead;
use App\Models\LeadStatus;

class SchemaResolver
{
    public function resolve(string $modelClass)
    {
        return match ($modelClass) {
            Lead::class => app(LeadFilterSchema::class),
            LeadStatus::class => app(LeadStatusFilterSchema::class),
            default => null,
        };
    }
}
