<?php

namespace App\DTOs;

class LeadData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $phone,
        public ?string $source,
        public string $type,

        public ?string $utmSource,
        public ?string $utmMedium,
        public ?string $utmCampaign,
        public ?string $utmTerm,
        public ?string $utmContent,

        public array $customFields,
        public int $tenantId
    ) {}

}
