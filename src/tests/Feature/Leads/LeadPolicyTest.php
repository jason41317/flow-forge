<?php

namespace Tests\Feature\Leads;

use App\Models\Tenant;
use App\Models\User;

it('prevents viewer from creating leads', function () {

    $tenant = Tenant::factory()->create();

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
        'role' => 'viewer',
    ]);

    $this->actingAs($user);

    $response = $this->postJson('/api/v1/leads', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
    ]);

    $response->assertForbidden();
});