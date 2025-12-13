<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'invited_by',
        'email',
        'token',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(32);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }
}
