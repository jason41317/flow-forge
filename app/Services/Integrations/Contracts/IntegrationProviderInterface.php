<?php

namespace App\Services\Integrations\Contracts;

use App\Models\Integration;
use App\Models\Lead;

interface IntegrationProviderInterface
{
    public function handle(Integration $integration, Lead $lead): void;
}
