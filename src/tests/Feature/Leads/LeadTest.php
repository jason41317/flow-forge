<?php

namespace Tests\Feature\Leads;

it('stores a lead', function () {

    $this->actingAsAdmin();

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
