<?php

namespace Database\Factories;

use App\Models\CvTailoringAgent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CvTailoringAgent>
 */
class CvTailoringAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'CV Tailoring Agent',
            'llm_model' => fake()->randomElement(['qwen3:8b', 'offline']),
        ];
    }
}
