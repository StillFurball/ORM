<?php

namespace Database\Factories;

use App\Models\EmailApplicationAgent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmailApplicationAgent>
 */
class EmailApplicationAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Email Application Agent',
            'safe_mode' => true,
        ];
    }
}
