<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRun extends Model
{
    protected $fillable = [
        'job_name',
        'status',
        'error_message',
        'duration_seconds',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }
}
