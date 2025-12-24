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
        'status',
        'location',
        'distance',
    ];

    protected $casts = [
        'date' => 'date',
        'duration' => 'decimal:2',
        'distance' => 'integer',
    ];

    const STATUS_READY_TO_INVOICE = 'ready_to_invoice';
    const STATUS_INVOICED = 'invoiced';
    const STATUS_PAID = 'paid';
    const STATUS_NON_PAID = 'non_paid';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_READY_TO_INVOICE => 'Ready to Invoice',
            self::STATUS_INVOICED => 'Invoiced',
            self::STATUS_PAID => 'Paid',
            self::STATUS_NON_PAID => 'Non-paid',
        ];
    }

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
        if (!$this->project) {
            return 0;
        }
        return $this->duration * $this->project->hourly_rate;
    }

    /**
     * Get hours (alias for duration for calendar)
     */
    public function getHoursAttribute(): float
    {
        return (float) $this->duration;
    }

    /**
     * Scope for ready to invoice registrations
     */
    public function scopeReadyToInvoice(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_READY_TO_INVOICE);
    }

    /**
     * Scope for invoiced registrations
     */
    public function scopeInvoiced(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INVOICED);
    }

    /**
     * Scope for paid registrations
     */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }
}
