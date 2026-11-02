<?php

namespace App\Models\Concerns;

use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasToolExecutionLogs
{
    /**
     * Kolom morph tidak bisa diberi foreign key, jadi cascade dilakukan di level model.
     */
    protected static function bootHasToolExecutionLogs(): void
    {
        static::deleting(fn (self $agent) => $agent->logs()->delete());
    }

    /**
     * Seluruh log eksekusi alat milik agent ini.
     *
     * @return MorphMany<ToolExecutionLog, $this>
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(ToolExecutionLog::class, 'loggable');
    }
}
