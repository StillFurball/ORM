<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChatSessionController extends Controller
{
    /**
     * Daftar sesi milik user: 2 query (sessions + COUNT messages dalam subquery).
     */
    public function index(Request $request): View
    {
        $chatSessions = $request->user()
            ->chatSessions()
            ->withCount('messages')
            ->latest()
            ->get();

        return view('chat-sessions.index', ['chatSessions' => $chatSessions]);
    }

    /**
     * Riwayat obrolan: pesan + log alat + agent dimuat dengan nested eager loading.
     */
    public function show(ChatSession $chatSession): View
    {
        Gate::authorize('view', $chatSession);

        $chatSession->load([
            'messages' => fn ($query) => $query->oldest(),
            'messages.toolExecutionLogs.loggable',
        ]);

        return view('chat-sessions.show', ['chatSession' => $chatSession]);
    }
}
