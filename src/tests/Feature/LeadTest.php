<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadField;
use App\Models\LeadFieldValue;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeadTest extends TestCase
{
    public function test_it_can_create_a_lead()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'type' => 'web_form',
            'source' => 'facebook',
            'custom_fields' => [
                'budget' => '5000'
            ]
        ];

        $response = $this->asTenant($user, $tenant)
            ->postJson('/api/v1/leads', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('leads', [
            'first_name' => 'John',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_user_cannot_see_other_tenant_leads()
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $userA = $this->createUser($tenantA);
        $userB = $this->createUser($tenantB);

        \App\Models\Lead::factory()->create([
            'tenant_id' => $tenantA->id,
            'first_name' => 'Hidden',
        ]);

        $response = $this->asTenant($userB, $tenantB)
            ->getJson('/api/v1/leads');

        $response->assertStatus(200);

        $response->assertJsonMissing([
            'first_name' => 'Hidden'
        ]);
    }
    
    public function test_it_stores_custom_fields()
    {
        $tenant = $this->createTenant();
        $user = $this->createUser($tenant);

        $field = \App\Models\LeadField::create([
            'tenant_id' => $tenant->id,
            'name' => 'Budget',
            'key' => 'budget',
            'type' => 'text',
        ]);

        $payload = [
            'first_name' => 'John',
            'type' => 'web_form',
            'custom_fields' => [
                'budget' => '10000'
            ]
        ];

        $this->asTenant($user, $tenant)
            ->postJson('/api/v1/leads', $payload)
            ->assertStatus(201);

        $this->assertDatabaseHas('lead_field_values', [
            'lead_field_id' => $field->id,
            'value' => '10000',
        ]);
    }

    public function test_user_can_filter_leads_by_type()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'type' => 'web_form',
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'type' => 'walk_in',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/leads?type=web_form');

        $response->assertStatus(200);

        expect($response->json('data'))->toHaveCount(1);
    }

    public function test_user_can_filter_leads_by_source()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'source' => 'facebook',
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'source' => 'google',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/leads?source=facebook');

        $response->assertStatus(200);

        expect($response->json('data'))->toHaveCount(1);
    }

    public function test_user_can_search_leads()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'first_name' => 'John',
        ]);

        Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'first_name' => 'Michael',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/leads?search=John');

        $response->assertStatus(200);

        expect($response->json('data'))->toHaveCount(1);
    }

    public function test_user_can_sort_leads_by_latest()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $oldLead = Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'created_at' => now()->subDay(),
        ]);

        $newLead = Lead::factory()->create([
            'tenant_id' => $tenant->id,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/leads?sort=-created_at');

        $response->assertStatus(200);

        expect($response->json('data.0.id'))
            ->toBe($newLead->id);
    }

    public function test_user_can_filter_by_custom_fields()
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $field = LeadField::factory()->create([
            'tenant_id' => $tenant->id,
            'key' => 'budget',
        ]);

        $leadOne = Lead::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $leadTwo = Lead::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        LeadFieldValue::factory()->create([
            'tenant_id' => $tenant->id,
            'lead_id' => $leadOne->id,
            'lead_field_id' => $field->id,
            'value' => '5000',
        ]);

        LeadFieldValue::factory()->create([
            'tenant_id' => $tenant->id,
            'lead_id' => $leadTwo->id,
            'lead_field_id' => $field->id,
            'value' => '10000',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/v1/leads?custom_fields[budget]=5000');

        $response->assertStatus(200);

        expect($response->json('data'))->toHaveCount(1);
    }

}
