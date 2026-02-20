<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description1',
        'description2',
        'image',
        'experience_years',
        'experience_text',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}