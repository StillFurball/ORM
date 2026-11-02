<?php

namespace Database\Factories;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatSession>
 */
class ChatSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(['Backend Laravel', 'Frontend React', 'Data Analyst', 'DevOps Engineer', 'Mobile Flutter', 'QA Engineer']);
        $location = fake()->randomElement(['Indonesia', 'Jakarta', 'Surabaya', 'Jerman', 'remote APAC']);

        return [
            'user_id' => User::factory(),
            'title' => "Lowongan {$role} di {$location}",
        ];
    }
}
