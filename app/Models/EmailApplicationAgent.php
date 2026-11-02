<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\EmailApplicationAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Agent yang menyusun draf email lamaran; safe mode = tidak ada email yang benar-benar terkirim.
 */
#[Fillable(['name', 'safe_mode'])]
class EmailApplicationAgent extends Model
{
    /** @use HasFactory<EmailApplicationAgentFactory> */
    use HasFactory, HasToolExecutionLogs;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'safe_mode' => 'boolean',
        ];
    }
}
