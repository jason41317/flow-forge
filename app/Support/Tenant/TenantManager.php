<?php

namespace App\Support\Tenant;

use App\Models\Tenant;

class TenantManager
{
    protected ?Tenant $tenant = null;

    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->tenant;
    }

    public function id(): ?int
    {
        return $this->tenant?->id;
    }

    public function resolve(): Tenant
    {
        return $this->tenant;
    }
}
