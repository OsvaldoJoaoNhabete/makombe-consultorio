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

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialty_id',
        'is_active',
        'photo',
        'must_change_password', // IMPORTANTE: Adicionar aqui
    ];

    /**
     * Os atributos que devem ser ocultados na serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'must_change_password' => 'boolean', // IMPORTANTE: Cast para boolean
    ];

    /**
     * Relação com a Especialidade.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    // NOVA RELAÇÃO: Disponibilidades
    public function availabilities()
    {
        return $this->hasMany(ProfessionalAvailability::class, 'user_id');
    }

    /**
     * Verifica se o utilizador tem uma foto de perfil.
     */
    public function hasPhoto(): bool
    {
        return !empty($this->photo);
    }

    /**
     * Obtém o URL da foto de perfil.
     */
    public function getPhotoUrl(): string
    {
        if ($this->hasPhoto()) {
            return asset('storage/' . $this->photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=6d28d9&size=128&font-size=0.4';
    }
}