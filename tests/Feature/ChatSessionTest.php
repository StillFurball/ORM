<?php

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\EmailApplicationAgent;
use App\Models\JobDiscoveryAgent;
use App\Models\JobMatchingAgent;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Buat satu sesi dengan sejumlah pesan, masing-masing punya 2 log alat.
 *
 * @param  class-string|null  $agentClass  Paksa semua log ke satu tipe agent; null = acak.
 */
function createChatSessionWithMessages(int $messageCount, ?string $agentClass = null): ChatSession
{
    $logs = ToolExecutionLog::factory(2);

    if ($agentClass !== null) {
        $logs = $logs->state(fn () => ['loggable_type' => $agentClass, 'loggable_id' => $agentClass::factory()]);
    }

    return ChatSession::factory()
        ->has(ChatMessage::factory($messageCount)->has($logs), 'messages')
        ->create();
}

/**
 * Hitung jumlah query yang dijalankan saat membuka halaman sesi.
 */
function countQueriesForShowPage(ChatSession $chatSession): int
{
    DB::flushQueryLog();
    DB::enableQueryLog();

    test()->actingAs($chatSession->user)
        ->get(route('chat-sessions.show', $chatSession))
        ->assertOk();

    return count(DB::getQueryLog());
}

test('relasi user, sesi, pesan, dan log saling terhubung', function () {
    $log = ToolExecutionLog::factory()->create();

    $message = $log->chatMessage;
    $chatSession = $message->chatSession;

    expect($message->toolExecutionLogs->modelKeys())->toBe([$log->id])
        ->and($chatSession->messages->modelKeys())->toBe([$message->id])
        ->and($chatSession->user->chatSessions->modelKeys())->toBe([$chatSession->id]);
});

test('log alat bersifat polymorphic ke berbagai agent JobPilot', function () {
    $discoveryAgent = JobDiscoveryAgent::factory()->create();
    $emailAgent = EmailApplicationAgent::factory()->create();

    $searchLog = ToolExecutionLog::factory()->for($discoveryAgent, 'loggable')->create();
    $draftLog = ToolExecutionLog::factory()->for($emailAgent, 'loggable')->create();

    expect($searchLog->loggable)->toBeInstanceOf(JobDiscoveryAgent::class)
        ->and($draftLog->loggable)->toBeInstanceOf(EmailApplicationAgent::class)
        ->and($searchLog->tool_name)->toBe('search_jobs')
        ->and($draftLog->tool_name)->toBe('draft_email')
        ->and($discoveryAgent->logs->modelKeys())->toBe([$searchLog->id])
        ->and($emailAgent->logs->modelKeys())->toBe([$draftLog->id]);
});

test('menghapus user ikut menghapus sesi, pesan, dan log (cascade)', function () {
    $chatSession = createChatSessionWithMessages(2);

    $chatSession->user->delete();

    expect(ChatSession::count())->toBe(0)
        ->and(ChatMessage::count())->toBe(0)
        ->and(ToolExecutionLog::count())->toBe(0);
});

test('menghapus agent ikut menghapus log miliknya', function () {
    $discoveryAgent = JobDiscoveryAgent::factory()->create();
    ToolExecutionLog::factory(2)->for($discoveryAgent, 'loggable')->create();
    $otherLog = ToolExecutionLog::factory()->for(JobMatchingAgent::factory(), 'loggable')->create();

    $discoveryAgent->delete();

    expect(ToolExecutionLog::pluck('id')->all())->toBe([$otherLog->id]);
});

test('halaman riwayat obrolan bebas N+1 query', function () {
    // morphTo eager load = 1 query per tipe agent, jadi tipe agent disamakan agar yang diuji hanya jumlah baris
    $smallSession = createChatSessionWithMessages(1, JobDiscoveryAgent::class);
    $largeSession = createChatSessionWithMessages(10, JobDiscoveryAgent::class);

    expect(countQueriesForShowPage($largeSession))->toBe(countQueriesForShowPage($smallSession));
});

test('halaman daftar hanya menampilkan sesi milik user beserta jumlah pesan', function () {
    $ownSession = createChatSessionWithMessages(3);
    $otherSession = createChatSessionWithMessages(1);

    $this->actingAs($ownSession->user)
        ->get(route('chat-sessions.index'))
        ->assertOk()
        ->assertSee($ownSession->title)
        ->assertSee('3 pesan')
        ->assertDontSee($otherSession->title);
});

test('user lain tidak boleh membuka sesi orang lain', function () {
    $chatSession = createChatSessionWithMessages(1);

    $this->actingAs(User::factory()->create())
        ->get(route('chat-sessions.show', $chatSession))
        ->assertForbidden();
});

test('tamu diarahkan ke halaman login', function () {
    $chatSession = createChatSessionWithMessages(1);

    $this->get(route('chat-sessions.index'))->assertRedirect(route('login'));
    $this->get(route('chat-sessions.show', $chatSession))->assertRedirect(route('login'));
});
