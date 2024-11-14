<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    use HasFactory;

    protected $table = 'job_logs';

    protected $fillable = [
        'class',
        'method',
        'parameters',
        'status',
        'max_retries',
        'retry_delay',
        'attempts',
        'error_message',
    ];

    // Cast 'parameters' JSON field to an array automatically
    protected $casts = [
        'parameters' => 'array',
    ];
}
