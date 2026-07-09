<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'price',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2, ',', '.') . ' MT';
    }

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'consulta' => '🩺 Consulta',
            'exame' => '🔬 Exame',
            'cirurgia' => '🏥 Cirurgia',
            'vacina' => '💉 Vacina',
            'tratamento' => '💊 Tratamento',
            'estetica' => '💅 Estética',
            'odontologia' => '🦷 Odontologia',
            default => '📋 Geral',
        };
    }
}