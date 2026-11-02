<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToolExecutionLog>
 */
class ToolExecutionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agent = fake()->randomElement([SecurityScanner::class, MalwareScanner::class, LogAnalyzer::class]);
        $status = fake()->randomElement(['success', 'failed']);

        return [
            'chat_message_id' => ChatMessage::factory(),
            'loggable_type' => $agent,
            'loggable_id' => $agent::factory(),
            'tool_name' => fake()->randomElement(['port_scan', 'hash_lookup', 'grep_logs', 'whois']),
            'input' => ['target' => fake()->ipv4()],
            'output' => $status === 'success' ? ['findings' => fake()->numberBetween(0, 5)] : null,
            'status' => $status,
            'duration_ms' => fake()->numberBetween(50, 5000),
        ];
    }
}
