<?php

namespace Database\Factories;

use App\Models\LeadField;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LeadField>
 */
class LeadFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::random(5),
            'key' => Str::random(5),
            'type' => 'text'
        ];
    }
}
