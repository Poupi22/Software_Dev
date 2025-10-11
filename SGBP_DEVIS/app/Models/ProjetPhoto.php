<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjetPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'projet_id',
        'path',
        'legende',
        'ordre',
        'principale',
    ];

    protected $casts = [
        'principale' => 'boolean',
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
