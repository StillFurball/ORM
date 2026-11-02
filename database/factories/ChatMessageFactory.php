<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * Contoh permintaan kandidat kepada asisten JobPilot.
     *
     * @var list<string>
     */
    private const USER_PROMPTS = [
        'Carikan lowongan backend Laravel remote yang menerima kandidat dari Indonesia.',
        'Mana lowongan yang paling cocok dengan profil saya?',
        'Sesuaikan CV saya untuk lowongan dengan skor tertinggi.',
        'Buatkan draf email lamaran untuk lowongan itu, jangan dikirim dulu.',
        'Tampilkan lowongan full-time dengan gaji minimal 10 juta.',
    ];

    /**
     * Contoh jawaban asisten JobPilot.
     *
     * @var list<string>
     */
    private const ASSISTANT_REPLIES = [
        'Saya menemukan 12 lowongan baru dari Jobicy dan Arbeitnow, 4 di antaranya cocok dengan kata kunci Anda.',
        'Lowongan teratas mendapat skor 86/100. Skill yang belum terpenuhi: Docker dan Redis.',
        'CV versi ATS sudah disesuaikan dan disimpan, silakan tinjau sebelum dipakai.',
        'Draf email lamaran siap ditinjau. Tidak ada email yang terkirim sebelum Anda menyetujuinya.',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_session_id' => ChatSession::factory(),
            'role' => fake()->randomElement(['user', 'assistant']),
            'content' => fn (array $attributes): string => fake()->randomElement(
                $attributes['role'] === 'user' ? self::USER_PROMPTS : self::ASSISTANT_REPLIES,
            ),
        ];
    }

    /**
     * Pesan yang ditulis kandidat (user).
     */
    public function fromUser(): static
    {
        return $this->state(['role' => 'user']);
    }

    /**
     * Pesan balasan asisten JobPilot.
     */
    public function fromAssistant(): static
    {
        return $this->state(['role' => 'assistant']);
    }
}
