<?php

namespace Tests\Feature\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

it('registers a user', function () {

    $response = $this->postJson('/api/v1/register', [
        'name' => 'John Doe',
        'email' => 'john@test.com',
        'password' => 'password',
        'tenant_name' => 'Acme Inc',
    ]);

    $response->assertOk();
});
