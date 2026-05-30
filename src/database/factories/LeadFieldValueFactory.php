<?php

namespace Database\Factories;

use App\Models\LeadFieldValue;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LeadFieldValue>
 */
class LeadFieldValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => fn () => auth()->user()?->tenant_id ?? Tenant::factory(),
            'value' => Str::random(5),
        ];
    }
}
