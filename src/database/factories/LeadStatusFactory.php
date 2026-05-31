<?php

namespace Database\Factories;

use App\Models\LeadStatus;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadStatus>
 */
class LeadStatusFactory extends Factory
{ 
    protected $model = LeadStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->unique()->word(),
            'color' => fake()->hexColor(),
            'sort_order' => 0,
            'is_default' => false,
            'is_closed' => false,
        ];
    }

    public function default(): static
    {
        return $this->state([
            'is_default' => true,
        ]);
    }

    public function closed(): static
    {
        return $this->state([
            'is_closed' => true,
        ]);
    }
}
