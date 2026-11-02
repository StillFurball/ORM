<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\LogAnalyzerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class LogAnalyzer extends Model
{
    /** @use HasFactory<LogAnalyzerFactory> */
    use HasFactory, HasToolExecutionLogs;
}
