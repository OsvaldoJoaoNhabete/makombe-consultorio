<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'insurance_id',
        'total_amount',
        'discount',
        'discount_type',
        'final_amount',
        'status',
        'notes',
        'valid_until',
        'sent_at',
        'approved_at',
        'rejected_at',
        'created_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // ============================================
    // RELAÇÕES
    // ============================================

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============================================
    // HELPERS
    // ============================================

    /**
     * Calcular total da cotação
     */
    public function calculateTotal(): self
    {
        $total = $this->items()->sum('total_price');
        
        // Aplicar desconto
        $discount = 0;
        if ($this->discount > 0) {
            if ($this->discount_type === 'percentage') {
                $discount = ($total * $this->discount) / 100;
            } else {
                $discount = $this->discount;
            }
        }
        
        $this->update([
            'total_amount' => $total,
            'final_amount' => max(0, $total - $discount),
        ]);
        
        return $this;
    }

    /**
     * Obter status formatado
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'rascunho' => '📝 Rascunho',
            'enviada' => '📤 Enviada',
            'aprovada' => '✅ Aprovada',
            'recusada' => '❌ Recusada',
            'paga' => '💰 Paga',
            'expirada' => '⏰ Expirada',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obter classe CSS do status
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'rascunho' => 'bg-gray-100 text-gray-800',
            'enviada' => 'bg-blue-100 text-blue-800',
            'aprovada' => 'bg-green-100 text-green-800',
            'recusada' => 'bg-red-100 text-red-800',
            'paga' => 'bg-purple-100 text-purple-800',
            'expirada' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Verificar se está expirada
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast() && !in_array($this->status, ['aprovada', 'paga']);
    }

    /**
     * Formatar valor total
     */
    public function getFormattedTotal(): string
    {
        return number_format($this->final_amount ?? $this->total_amount, 2, ',', '.') . ' MT';
    }

    /**
     * Formatar desconto
     */
    public function getFormattedDiscount(): string
    {
        if (!$this->discount || $this->discount <= 0) {
            return '0 MT';
        }
        
        if ($this->discount_type === 'percentage') {
            return number_format($this->discount, 0) . '%';
        }
        
        return number_format($this->discount, 2, ',', '.') . ' MT';
    }
}