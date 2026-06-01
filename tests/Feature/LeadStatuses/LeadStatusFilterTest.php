<?php

use App\Models\LeadStatus;

it('filters lead statuses using eq operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create([
        'tenant_id' => $user->tenant_id,
        'name' => 'New',
    ]);
    LeadStatus::factory()->create(['name' => 'Updated']);

    $response = $this->getJson(
        '/api/v1/lead-statuses?filters[name][eq]=New'
    );

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('filters lead statuses using contains operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create([
        'tenant_id' => $user->tenant_id,
        'name' => 'New',
    ]);

    LeadStatus::factory()->create([
        'name' => 'Updated',
    ]);

    $response = $this->getJson('/api/v1/lead-statuses?filters[name][contains]=ne');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('filters lead statuses using gt operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create(['id' => 1]);
    LeadStatus::factory()->create(['id' => 10]);

    $response = $this->getJson('/api/v1/lead-statuses?filters[id][gt]=5');

    $response->assertOk();

    expect($response->json('data'))
        ->each(fn ($lead) => expect($lead->value['id'])->toBeGreaterThan(5));
});

it('filters lead statuses using lt operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);
    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);

    $response = $this->getJson('/api/v1/lead-statuses?filters[id][lt]=5');

    $response->assertOk();

    expect($response->json('data'))
        ->each(fn ($lead) => expect($lead->value['id'])->toBeLessThan(5));
});

it('filters lead statuses using between operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);
    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 5]);
    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);

    $response = $this->getJson(
        '/api/v1/lead-statuses?filters[id][between]=[2,8]'
    );

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('sorts lead statuses ascending', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);
    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);

    $response = $this->getJson('/api/v1/lead-statuses?sort=id');

    $response->assertOk();

    $ids = collect($response->json('data'))->pluck('id')->toArray();

    expect($ids)->toBe([1, 10]);
});

it('sorts lead statuses descending', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);
    LeadStatus::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);

    $response = $this->getJson('/api/v1/lead-statuses?sort=-id');

    $response->assertOk();

    $ids = collect($response->json('data'))->pluck('id')->toArray();

    expect($ids)->toBe([10, 1]);
});
