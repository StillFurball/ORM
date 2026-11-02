<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\JobMatchingAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Agent yang menilai kecocokan lowongan dengan profil kandidat memakai LLM.
 */
#[Fillable(['name', 'llm_model'])]
class JobMatchingAgent extends Model
{
    /** @use HasFactory<JobMatchingAgentFactory> */
    use HasFactory, HasToolExecutionLogs;
}
