<?php

use App\Models\Lead;
use App\Models\LeadField;
use App\Models\LeadFieldValue;
use Illuminate\Support\Facades\Log;

it('filters leads using eq operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create([
        'tenant_id' => $user->tenant_id,
        'first_name' => 'John',
    ]);
    Lead::factory()->create(['first_name' => 'Jane']);

    $response = $this->getJson(
        '/api/v1/leads?filters[first_name][eq]=John'
    );

    // Log::info($this->tenant)
    // Log::info($response->json('data'));
    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('filters leads using contains operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create([
        'tenant_id' => $user->tenant_id,
        'email' => 'john@test.com',
    ]);

    Lead::factory()->create([
        'email' => 'alice@test.com',
    ]);

    $response = $this->getJson('/api/v1/leads?filters[email][contains]=john');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('filters leads using gt operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create(['id' => 1]);
    Lead::factory()->create(['id' => 10]);

    $response = $this->getJson('/api/v1/leads?filters[id][gt]=5');

    $response->assertOk();

    expect($response->json('data'))
        ->each(fn ($lead) => expect($lead->value['id'])->toBeGreaterThan(5));
});

it('filters leads using lt operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);
    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);

    $response = $this->getJson('/api/v1/leads?filters[id][lt]=5');

    $response->assertOk();

    expect($response->json('data'))
        ->each(fn ($lead) => expect($lead->value['id'])->toBeLessThan(5));
});

it('filters leads using between operator', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);
    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 5]);
    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);

    $response = $this->getJson(
        '/api/v1/leads?filters[id][between]=[2,8]'
    );

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(1);
});

it('sorts leads ascending', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);
    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);

    $response = $this->getJson('/api/v1/leads?sort=id');

    $response->assertOk();

    $ids = collect($response->json('data'))->pluck('id')->toArray();

    expect($ids)->toBe([1, 10]);
});

it('sorts leads descending', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 10]);
    Lead::factory()->create(['tenant_id' => $user->tenant_id, 'id' => 1]);

    $response = $this->getJson('/api/v1/leads?sort=-id');

    $response->assertOk();

    $ids = collect($response->json('data'))->pluck('id')->toArray();

    expect($ids)->toBe([10, 1]);
});

it('filters leads using custom fields', function () {

    $user = $this->createTenantAdmin();

    $this->actingAs($user);

    $lead = Lead::factory()->create(['tenant_id' => $user->tenant_id]);

    $leadField = LeadField::factory()
        ->create([
            'key' => 'budget',
            'tenant_id' => $user->tenant_id,
        ]);

    LeadFieldValue::factory()
        ->create([
            'lead_id' => $lead->id,
            'lead_field_id' => $leadField->id,
        ]);

    // assume field + value already exist in DB
    // budget = 5000

    $response = $this->getJson(
        '/api/v1/leads?custom_fields[budget][eq]=5000'
    );

    $response->assertOk();
});
