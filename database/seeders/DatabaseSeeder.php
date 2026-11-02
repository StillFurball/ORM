<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\CvTailoringAgent;
use App\Models\EmailApplicationAgent;
use App\Models\JobDiscoveryAgent;
use App\Models\JobMatchingAgent;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Hasil: 5 user, 15 sesi, 60 pesan, 60 log alat, 4 agent JobPilot.
     */
    public function run(): void
    {
        $discovery = JobDiscoveryAgent::factory()->create(['source' => 'jobicy']);
        $matching = JobMatchingAgent::factory()->create(['llm_model' => 'qwen3:8b']);
        $tailoring = CvTailoringAgent::factory()->create(['llm_model' => 'qwen3:8b']);
        $emailing = EmailApplicationAgent::factory()->create(['safe_mode' => true]);

        $demoUser = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);
        $users = User::factory(4)->create()->prepend($demoUser);

        foreach ($users as $user) {
            foreach (ChatSession::factory(3)->for($user)->create() as $chatSession) {
                // Alur JobPilot: cari & nilai lowongan, lalu siapkan CV dan draf email lamaran.
                ChatMessage::factory()->for($chatSession)->fromUser()
                    ->create(['content' => "Carikan {$chatSession->title} yang cocok dengan profil saya."]);

                ChatMessage::factory()->for($chatSession)->fromAssistant()
                    ->has(ToolExecutionLog::factory()->for($discovery, 'loggable'))
                    ->has(ToolExecutionLog::factory()->for($matching, 'loggable'))
                    ->create(['content' => 'Saya menemukan 12 lowongan baru; lowongan teratas mendapat skor 86/100.']);

                ChatMessage::factory()->for($chatSession)->fromUser()
                    ->create(['content' => 'Siapkan CV dan draf email lamaran untuk lowongan teratas, jangan dikirim dulu.']);

                ChatMessage::factory()->for($chatSession)->fromAssistant()
                    ->has(ToolExecutionLog::factory()->for($tailoring, 'loggable'))
                    ->has(ToolExecutionLog::factory()->for($emailing, 'loggable'))
                    ->create(['content' => 'CV versi ATS dan draf email siap ditinjau. Tidak ada email yang terkirim sebelum Anda menyetujuinya.']);
            }
        }
    }
}
