<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'icon',
        'image_path',
        'description',
        'description_courte',
        'ordre',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('ordre');
    }
}
