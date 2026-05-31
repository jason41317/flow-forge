<?php

use App\Models\Lead;

it('creates an audit log when a lead is created', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $this->postJson('/api/v1/leads', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'source' => 'web',
        'type' => 'inquiry',
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'event' => 'created',
        'entity_type' => 'lead',
    ]);
});

it('creates an audit log when a lead is updated', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $lead = Lead::factory()->create();

    $this->putJson('/api/v1/leads/' . $lead->id, [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'source' => 'web',
        'type' => 'inquiry',
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'event' => 'updated',
        'entity_type' => 'lead',
    ]);
});
