<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\JobDiscoveryAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Agent pencari lowongan dari sumber kerja (Jobicy, Arbeitnow, JSearch, ...).
 */
#[Fillable(['name', 'source'])]
class JobDiscoveryAgent extends Model
{
    /** @use HasFactory<JobDiscoveryAgentFactory> */
    use HasFactory, HasToolExecutionLogs;
}
