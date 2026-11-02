<?php

namespace Database\Factories;

use App\Models\JobDiscoveryAgent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobDiscoveryAgent>
 */
class JobDiscoveryAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Job Discovery Agent',
            'source' => fake()->randomElement(['jobicy', 'arbeitnow', 'jsearch', 'jooble', 'adzuna']),
        ];
    }
}
