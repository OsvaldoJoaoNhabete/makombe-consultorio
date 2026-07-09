<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log($patientId, $action, $description = null, $metadata = [], $userId = null)
    {
        return static::create([
            'patient_id' => $patientId,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
        ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}