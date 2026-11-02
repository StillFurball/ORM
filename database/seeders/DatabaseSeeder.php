<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $chatSessions = ChatSession::factory(3)->has(
            ChatMessage::factory(4)->has(ToolExecutionLog::factory(2)),
            'messages',
        );

        User::factory()->has($chatSessions)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(4)->has($chatSessions)->create();
    }
}
