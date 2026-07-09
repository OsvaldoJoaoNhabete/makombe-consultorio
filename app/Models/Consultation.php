<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'scheduled_at',
        'type',
        'status',
        'insurance_id',
        'total_amount',
        'insurance_coverage',
        'patient_amount',
        'payment_status',
        'location',
        'notes',
        'clinical_notes',
        'diagnosis',
        'prescription',
        'observations',
        'created_by',
        'video_call_started_at',
        'video_call_ended_at',
        'patient_notified_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'insurance_coverage' => 'decimal:2',
        'patient_amount' => 'decimal:2',
        'video_call_started_at' => 'datetime',
        'video_call_ended_at' => 'datetime',
        'patient_notified_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isVideoCallActive(): bool
    {
        return $this->type === 'teleconsulta'
            && $this->video_call_started_at !== null
            && $this->video_call_ended_at === null;
    }

    public function getJitsiRoomId(): string
    {
        if ($this->location && str_starts_with($this->location, 'https://meet.jit.si/')) {
            return str_replace('https://meet.jit.si/', '', $this->location);
        }
        return '';
    }

    public function startVideoCall(): void
    {
        $this->update([
            'video_call_started_at' => now(),
            'status' => 'em_andamento',
        ]);
    }

    public function endVideoCall(): void
    {
        $this->update([
            'video_call_ended_at' => now(),
        ]);
    }
}