<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'full_name',
        'nid',
        'bi_number',
        'birth_date',
        'gender',
        'phone',
        'email',
        'address',
        'medical_history',
        'password',
        'email_verified_at',
        'is_active',
        'created_by',
        'photo_path',
        'password_reset_token',
        'password_reset_expires',
        'first_login_at', // ← NOVO
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'birth_date' => 'date',
    'email_verified_at' => 'datetime',
    'password_reset_expires' => 'datetime',
    'first_login_at' => 'datetime', // ← NOVO
    'is_active' => 'boolean',
    'password' => 'hashed',
];

    // ============================================
    // RELAÇÕES
    // ============================================

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function insurances(): BelongsToMany
    {
        return $this->belongsToMany(Insurance::class, 'patient_insurances')
                    ->withPivot('policy_number', 'valid_from', 'valid_until', 'is_primary', 'is_active')
                    ->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(PatientActivityLog::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

// ============================================
// MÉTODOS DE CONTROLO DE PRIMEIRO ACESSO
// ============================================

/**
 * Verificar se é o primeiro acesso (nunca alterou a senha)
 */
public function isFirstAccess(): bool
{
    return $this->first_login_at === null;
}

/**
 * Marcar primeiro acesso como feito
 */
public function markFirstAccessDone(): void
{
    $this->update(['first_login_at' => now()]);
}

/**
 * Verificar se precisa alterar senha (primeiro acesso ou senha muito antiga)
 */
public function needsPasswordChange(): bool
{
    return $this->first_login_at === null;
}


    // ============================================
    // HELPERS
    // ============================================

    public static function generateNextNid(): string
    {
        $year = date('Y');
        $prefix = 'MAC-P-' . $year . '-';
        
        $lastPatient = static::withTrashed()
            ->where('nid', 'LIKE', $prefix . '%')
            ->get()
            ->sortByDesc(function($p) use ($prefix) {
                return (int) substr($p->nid, strlen($prefix));
            })
            ->first();
        
        $nextNumber = $lastPatient ? (int) substr($lastPatient->nid, strlen($prefix)) + 1 : 1;
        
        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public static function findByEmailOrPhone(string $identifier): ?self
    {
        $identifier = trim($identifier);
        
        $patient = static::where('email', $identifier)->first();
        if ($patient) return $patient;
        
        $cleanedPhone = self::cleanPhone($identifier);
        if (strlen($cleanedPhone) === 9) {
            return static::where('is_active', true)
                ->get()
                ->first(function ($p) use ($cleanedPhone) {
                    return self::cleanPhone($p->phone) === $cleanedPhone;
                });
        }
        
        return null;
    }

    public static function cleanPhone(?string $phone): string
    {
        if (!$phone) return '';
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleaned, '258') && strlen($cleaned) === 12) {
            $cleaned = substr($cleaned, 3);
        }
        if (strlen($cleaned) > 9) {
            $cleaned = substr($cleaned, -9);
        }
        return $cleaned;
    }

    public function getPhotoUrl(): string
    {
        if ($this->photo_path && file_exists(public_path('storage/' . $this->photo_path))) {
            return asset('storage/' . $this->photo_path);
        }
        return '';
    }

    public function hasPhoto(): bool
    {
        return !empty($this->photo_path) && file_exists(public_path('storage/' . $this->photo_path));
    }

    public function getInitial(): string
    {
        return strtoupper(substr($this->full_name ?? '?', 0, 1));
    }

    public function getAvatarColor(): string
    {
        $colors = [
            'from-blue-500 to-indigo-600',
            'from-purple-500 to-pink-600',
            'from-green-500 to-emerald-600',
            'from-amber-500 to-orange-600',
            'from-red-500 to-rose-600',
            'from-teal-500 to-cyan-600',
        ];
        return $colors[ord($this->getInitial()) % count($colors)];
    }
}