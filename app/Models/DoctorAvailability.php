<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Dias da semana em português
    public static function getDaysOfWeek(): array
    {
        return [
            'monday' => 'Segunda-feira',
            'tuesday' => 'Terça-feira',
            'wednesday' => 'Quarta-feira',
            'thursday' => 'Quinta-feira',
            'friday' => 'Sexta-feira',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo',
        ];
    }

    // Obter nome do dia em português
    public function getDayNameAttribute(): string
    {
        return self::getDaysOfWeek()[$this->day_of_week] ?? $this->day_of_week;
    }

    // Relacionamento com User (Médico)
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope para disponíveis
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope para um dia específico
    public function scopeForDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }
}