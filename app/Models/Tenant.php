<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'default_currency_id',
        'client_limit',
        'project_limit',
        'user_limit',
        'custom_templates',
        'project_number_format',
        'distance_unit',
        'mileage_allowance',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function defaultCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id');
    }

    public function generateProjectNumber(): string
    {
        $format = $this->project_number_format ?? 'PROJ-{YYYY}-{####}';
        $year = now()->format('Y');
        
        // Extract the number format {####} to determine padding
        preg_match('/\{(#+)\}/', $format, $matches);
        $padding = isset($matches[1]) ? strlen($matches[1]) : 4;
        
        // Get the last project number for this year
        $lastProject = $this->projects()
            ->whereYear('created_at', $year)
            ->whereNotNull('project_number')
            ->orderBy('created_at', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastProject && $lastProject->project_number) {
            // Extract the number from the last project number
            preg_match('/\d+$/', $lastProject->project_number, $numberMatches);
            if (isset($numberMatches[0])) {
                $nextNumber = intval($numberMatches[0]) + 1;
            }
        }
        
        // Generate the project number
        $projectNumber = str_replace('{YYYY}', $year, $format);
        $projectNumber = preg_replace('/\{#+\}/', str_pad($nextNumber, $padding, '0', STR_PAD_LEFT), $projectNumber);
        
        return $projectNumber;
    }
}
