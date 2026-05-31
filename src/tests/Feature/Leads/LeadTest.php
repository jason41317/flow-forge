<?php

namespace Tests\Feature\Leads;

use App\Models\Lead;

it('stores a lead', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $response = $this->postJson('/api/v1/leads', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'phone' => '123456',
        'source' => 'web',
        'type' => 'inquiry',
    ]);

    $response->assertCreated();
});

it('updates a lead', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $lead = Lead::factory()->create([
        'tenant_id' => $user->tenant_id,
    ]);

    $response = $this->putJson('/api/v1/leads/' . $lead->id, [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'source' => 'web',
        'type' => 'inquiry',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('leads', [
        'id' => $lead->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'source' => 'web',
        'type' => 'inquiry',
    ]);
});
