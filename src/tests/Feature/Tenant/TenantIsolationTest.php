<?php

namespace Tests\Feature\Tenant;

use App\Models\Lead;
use App\Models\User;
use App\Models\Tenant;

it('prevents cross tenant access', function () {

    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();

    $userA = User::factory()->admin()->create([
        'tenant_id' => $tenantA->id,
    ]);

    $this->actingAs($userA);

    Lead::factory()->forTenant($tenantA->id)->create();
    Lead::factory()->forTenant($tenantB->id)->create();

    $response = $this->getJson('/api/v1/leads');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});