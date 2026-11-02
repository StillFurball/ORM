<?php

namespace Database\Factories;

use App\Models\JobMatchingAgent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobMatchingAgent>
 */
class JobMatchingAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Job Matching Agent',
            'llm_model' => fake()->randomElement(['qwen3:8b', 'offline']),
        ];
    }
}
