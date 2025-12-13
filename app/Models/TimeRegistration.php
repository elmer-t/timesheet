<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class TimeRegistration extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'client_id',
        'project_id',
        'date',
        'duration',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'duration' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Calculate revenue based on project hourly rate
     */
    public function getRevenueAttribute(): float
    {
        return $this->duration * $this->project->hourly_rate;
    }
}
