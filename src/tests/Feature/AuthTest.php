<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_create_tenant()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'tenant_name' => 'FlowForge',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Registered successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@test.com',
        ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'FlowForge',
        ]);
    }

    public function test_user_can_login_and_receive_token()
    {
        $tenant = $this->createTenant();

        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }

    public function test_guest_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/v1/leads');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_leads()
    {
        $tenant = \App\Models\Tenant::factory()->create();

        $user = \App\Models\User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'X-Tenant' => $tenant->slug,
        ])->getJson('/api/v1/leads');

        $response->assertStatus(200);
    }

}
