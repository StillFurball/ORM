<?php

namespace App\Models;

use App\Models\Concerns\HasToolExecutionLogs;
use Database\Factories\SecurityScannerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class SecurityScanner extends Model
{
    /** @use HasFactory<SecurityScannerFactory> */
    use HasFactory, HasToolExecutionLogs;
}
