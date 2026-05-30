<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function actingAsAdmin(array $overrides = [])
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()
            ->admin()
            ->create(array_merge([
                'tenant_id' => $tenant->id,
            ], $overrides));

        return $this->actingAs($user);
    }
}
