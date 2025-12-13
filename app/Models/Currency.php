<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'sign',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tenantsAsDefault(): HasMany
    {
        return $this->hasMany(Tenant::class, 'default_currency_id');
    }
}
