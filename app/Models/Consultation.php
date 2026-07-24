<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Specialty;


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
        'specialty_id',
        'is_urgent',
        'rating',
        'review_comment',
        'reviewed_at',
        'rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'video_call_started_at' => 'datetime',
        'video_call_ended_at' => 'datetime',
        'patient_notified_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'insurance_coverage' => 'decimal:2',
        'patient_amount' => 'decimal:2',
        'is_urgent' => 'boolean',
        'rating' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function isReviewed(): bool
    {
        return $this->rating !== null;
    }

    public function getStarsAttribute(): string
    {
        if (!$this->rating) return '';
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }



    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Obter classe CSS do status para badges
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'agendada' => 'bg-blue-100 text-blue-800',
            'confirmada' => 'bg-indigo-100 text-indigo-800',
            'em_andamento' => 'bg-amber-100 text-amber-800',
            'concluida' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            'faltou' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Obter label formatada do status
     */
    public function getStatusLabel(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Verificar se a videochamada está ativa (começou e não terminou)
     */
    public function isVideoCallActive(): bool
    {
        return $this->type === 'teleconsulta' && 
               $this->video_call_started_at !== null && 
               $this->video_call_ended_at === null;
    }

    /**
     * Obter ID da sala Jitsi a partir do location
     */
    public function getJitsiRoomId(): ?string
    {
        if ($this->location && str_starts_with($this->location, 'https://meet.jit.si/')) {
            return str_replace('https://meet.jit.si/', '', $this->location);
        }
        return null;
    }

    /**
     * Iniciar videochamada
     */
    public function startVideoCall(): void
    {
        $this->update([
            'video_call_started_at' => now(),
            'status' => 'em_andamento'
        ]);
    }

    /**
     * Terminar videochamada
     */
    public function endVideoCall(): void
    {
        $this->update([
            'video_call_ended_at' => now()
        ]);
    }

    public function rating()
    {
        return $this->hasOne(ConsultationRating::class, 'consultation_id');
    }
}