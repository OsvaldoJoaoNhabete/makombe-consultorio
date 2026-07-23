<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_active',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relação inversa: Uma especialidade pode ter vários utilizadores (médicos).
     */
    public function users()
    {
        return $this->hasMany(User::class, 'specialty_id');
    }

    /**
     * Scope para filtrar apenas especialidades ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obter o nome da especialidade.
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}