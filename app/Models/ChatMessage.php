<?php

namespace App\Models;

use Database\Factories\ChatMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['chat_session_id', 'role', 'content'])]
class ChatMessage extends Model
{
    /** @use HasFactory<ChatMessageFactory> */
    use HasFactory;

    /**
     * Sesi obrolan tempat pesan ini berada.
     *
     * @return BelongsTo<ChatSession, $this>
     */
    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }

    /**
     * Log pemanggilan alat yang dipicu oleh pesan ini.
     *
     * @return HasMany<ToolExecutionLog, $this>
     */
    public function toolExecutionLogs(): HasMany
    {
        return $this->hasMany(ToolExecutionLog::class);
    }
}
