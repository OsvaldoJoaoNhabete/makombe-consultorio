<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ============================================
    // HELPERS DE FOTO
    // ============================================

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
        return strtoupper(substr($this->name ?? '?', 0, 1));
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
            'from-indigo-500 to-purple-600',
            'from-pink-500 to-rose-600',
        ];
        
        $index = ord($this->getInitial()) % count($colors);
        return $colors[$index];
    }
}