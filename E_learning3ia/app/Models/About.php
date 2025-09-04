<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'mission',
        'vision',
        'valeurs',
        'image',
        'video',
        'lien',
        'statut',
    ];

    protected $casts = [
        'valeurs' => 'array', // Si vous stockez des valeurs en JSON
    ];
}