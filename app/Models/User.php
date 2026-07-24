<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'is_active',
        'specialty_id',
        'photo',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    // Relação com Especialidade
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    // Relação com Disponibilidades
    public function availabilities()
    {
        return $this->hasMany(ProfessionalAvailability::class, 'user_id');
    }

    // Verificar se tem foto
    public function hasPhoto(): bool
    {
        return !empty($this->photo);
    }

    // Obter URL da foto
    public function getPhotoUrl(): string
    {
        if ($this->hasPhoto()) {
            return asset('storage/' . $this->photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=6d28d9&size=128&font-size=0.4';
    }

    // Verificar se é paciente
    public function isPatient(): bool
    {
        return $this->type === 'patient';
    }

    // Verificar se é staff
    public function isStaff(): bool
    {
        return $this->type === 'staff';
    }
}