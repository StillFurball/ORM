<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\CvTailoringAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Agent yang menyesuaikan CV kandidat dengan lowongan tertentu.
 */
#[Fillable(['name', 'llm_model'])]
class CvTailoringAgent extends Model
{
    /** @use HasFactory<CvTailoringAgentFactory> */
    use HasFactory, HasToolExecutionLogs;
}
