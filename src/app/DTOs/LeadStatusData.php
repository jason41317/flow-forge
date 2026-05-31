<?php

namespace App\DTOs;

class LeadStatusData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public ?string $color,
        public ?bool $isDefault,
        public ?bool $isClosed,
        public int $tenantId
    ) {}
}
