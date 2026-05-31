<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createTenantAdmin()
    {
        $tenant = Tenant::factory()->create();

        return User::factory()
            ->admin()
            ->create([
                'tenant_id' => $tenant->id,
            ]);
    }
}
