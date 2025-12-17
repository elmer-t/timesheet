<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'currency_id',
        'project_number',
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'hourly_rate',
        'mileage_allowance',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check() && auth()->user()->tenant_id) {
                $builder->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function timeRegistrations(): HasMany
    {
        return $this->hasMany(TimeRegistration::class);
    }

    /**
     * Check if project can be selected for time registration
     */
    public function canRegisterTime(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $today = Carbon::today();
        
        if ($today->isBefore($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->isAfter($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Scope for projects available for time registration
     */
    public function scopeAvailableForRegistration(Builder $query): Builder
    {
        $today = Carbon::today();
        
        return $query->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $today);
            });
    }
}
