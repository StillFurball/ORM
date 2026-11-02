<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\CvTailoringAgent;
use App\Models\EmailApplicationAgent;
use App\Models\JobDiscoveryAgent;
use App\Models\JobMatchingAgent;
use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToolExecutionLog>
 */
class ToolExecutionLogFactory extends Factory
{
    /**
     * Nama alat yang dipanggil oleh setiap tipe agent JobPilot.
     *
     * @var array<class-string, string>
     */
    private const TOOL_NAMES = [
        JobDiscoveryAgent::class => 'search_jobs',
        JobMatchingAgent::class => 'score_match',
        CvTailoringAgent::class => 'tailor_cv',
        EmailApplicationAgent::class => 'draft_email',
    ];

    /**
     * Define the model's default state.
     *
     * Nama alat, input, dan output menyesuaikan tipe agent, termasuk saat agent di-set lewat for($agent, 'loggable').
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agent = fake()->randomElement(array_keys(self::TOOL_NAMES));

        return [
            'chat_message_id' => ChatMessage::factory(),
            'loggable_type' => $agent,
            'loggable_id' => $agent::factory(),
            'tool_name' => fn (array $attributes): string => self::TOOL_NAMES[$attributes['loggable_type']],
            'input' => fn (array $attributes): array => $this->inputFor($attributes['loggable_type']),
            'status' => fake()->randomElement(['success', 'success', 'success', 'failed']),
            'output' => fn (array $attributes): ?array => $attributes['status'] === 'success'
                ? $this->outputFor($attributes['loggable_type'])
                : null,
            'duration_ms' => fake()->numberBetween(200, 8000),
        ];
    }

    /**
     * @param  class-string  $agent
     * @return array<string, mixed>
     */
    private function inputFor(string $agent): array
    {
        $jobListingId = fake()->numberBetween(1, 500);

        return match ($agent) {
            JobDiscoveryAgent::class => [
                'keywords' => fake()->randomElements(['laravel', 'php', 'react', 'python', 'devops', 'flutter'], 2),
                'countries' => ['id'],
                'employment_types' => ['full-time'],
                'max_jobs' => 20,
            ],
            JobMatchingAgent::class => ['job_listing_id' => $jobListingId, 'candidate_profile_id' => fake()->numberBetween(1, 50)],
            CvTailoringAgent::class => ['job_listing_id' => $jobListingId, 'template' => 'ats'],
            EmailApplicationAgent::class => ['job_listing_id' => $jobListingId, 'recipient_email' => fake()->companyEmail()],
        };
    }

    /**
     * @param  class-string  $agent
     * @return array<string, mixed>
     */
    private function outputFor(string $agent): array
    {
        return match ($agent) {
            JobDiscoveryAgent::class => [
                'jobs_discovered' => fake()->numberBetween(5, 40),
                'jobs_new' => fake()->numberBetween(0, 15),
                'sources' => ['jobicy', 'arbeitnow'],
            ],
            JobMatchingAgent::class => [
                'score' => fake()->randomFloat(1, 40, 98),
                'matched_skills' => fake()->randomElements(['Laravel', 'MySQL', 'REST API', 'Git', 'Tailwind CSS'], 3),
                'missing_requirements' => fake()->randomElements(['Docker', 'Redis', 'Kubernetes', 'AWS'], 2),
            ],
            CvTailoringAgent::class => [
                'cv_file_path' => 'cvs/'.fake()->uuid().'.pdf',
                'cv_file_hash' => hash('sha256', fake()->uuid()),
            ],
            EmailApplicationAgent::class => [
                'subject' => 'Lamaran Posisi '.fake()->jobTitle(),
                'content_hash' => hash('sha256', fake()->uuid()),
                'status' => 'draft',
            ],
        };
    }
}
