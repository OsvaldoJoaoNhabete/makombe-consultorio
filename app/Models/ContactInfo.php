<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'label', 'value', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getByType($type)
    {
        return self::where('type', $type)->where('is_active', true)
            ->orderBy('order')->get();
    }
}