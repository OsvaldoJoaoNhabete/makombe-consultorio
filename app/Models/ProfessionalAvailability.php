<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mapeamento dos dias da semana em Português
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

    public function getDayOfWeekPt(): string
    {
        return self::getDaysOfWeek()[$this->day_of_week] ?? '';
    }

    public function getFormattedTime(): string
    {
        return date('H\hi', strtotime($this->start_time)) . ' - ' . date('H\hi', strtotime($this->end_time));
    }
}