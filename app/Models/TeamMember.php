<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'position', 'description', 'photo_path',
        'facebook', 'linkedin', 'whatsapp', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }

    public function deletePhoto()
    {
        if ($this->photo_path && Storage::exists($this->photo_path)) {
            Storage::delete($this->photo_path);
        }
    }
}