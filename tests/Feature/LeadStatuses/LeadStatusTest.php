<?php

use App\Models\LeadStatus;

it('lists tenant status', function () {
    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->count(3)->create([
        'tenant_id' => $user->tenant_id,
    ]);

    $response = $this->getJson('/api/v1/lead-statuses');

    $response->assertOk();

    expect($response->json('data'))
        ->toHaveCount(3);
});

it('cannot see statuses from another tenant', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->count(2)->create([
        'tenant_id' => $user->tenant_id,
    ]);

    LeadStatus::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/lead-statuses');

    $response->assertOk();

    expect($response->json('data'))
        ->toHaveCount(2);
});

it('creates a status', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $response = $this->postJson('/api/v1/lead-statuses', [
        'name' => 'Qualified',
        'color' => '#22c55e',
        'sort_order' => 1,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('lead_statuses', [
        'name' => 'Qualified',
    ]);
});

it('validates status creation', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $response = $this->postJson('/api/v1/lead-statuses', []);

    $response->assertUnprocessable();

    $response->assertJsonValidationErrors([
        'name',
    ]);
});

it('cannot update another tenant status', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);
    $otherStatus = LeadStatus::factory()->create();

    $response = $this->putJson(
        "/api/v1/lead-statuses/{$otherStatus->id}",
        [
            'name' => 'Updated',
        ]
    );

    $response->assertStatus(404);
});

it('cannot delete default status', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $status = LeadStatus::factory()->create([
        'tenant_id' => $user->tenant_id,
        'is_default' => true,
    ]);

    $response = $this->deleteJson(
        "/api/v1/lead-statuses/{$status->id}"
    );

    $response->assertForbidden();
});
