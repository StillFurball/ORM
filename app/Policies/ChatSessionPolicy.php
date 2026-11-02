<?php

namespace App\Policies;

use App\Models\ChatSession;
use App\Models\User;

class ChatSessionPolicy
{
    /**
     * Hanya pemilik sesi yang boleh membuka riwayat obrolannya.
     */
    public function view(User $user, ChatSession $chatSession): bool
    {
        return $chatSession->user_id === $user->id;
    }
}
