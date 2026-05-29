<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createTenant(): Tenant
    {
        return Tenant::factory()->create();
    }

    protected function createUser(Tenant $tenant): User
    {
        return User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
    }

    protected function actingAsTenantUser(Tenant $tenant): User
    {
        return $this->createUser($tenant);
    }

    protected function asTenant(User $user, Tenant $tenant)
    {
        return $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'X-Tenant' => $tenant->slug,
            ]);
    }
}
