<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'address',
        'coverage_percentage',
        'logo_path',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'coverage_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_insurances')
                    ->withPivot('policy_number', 'valid_from', 'valid_until', 'is_primary', 'is_active')
                    ->withTimestamps();
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCoverageFormatted(): string
    {
        return number_format($this->coverage_percentage ?? 0, 0) . '%';
    }

    public function getLogoUrl(): string
    {
        if ($this->logo_path && file_exists(public_path('storage/' . $this->logo_path))) {
            return asset('storage/' . $this->logo_path);
        }
        return asset('images/default-insurance.png');
    }
}