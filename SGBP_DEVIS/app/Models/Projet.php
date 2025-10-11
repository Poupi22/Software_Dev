<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'client_nom',
        'lieu',
        'categorie',
        'image_path',
        'description',
        'duree',
        'superficie',
        'annee',
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

    // Relation avec les photos
    public function photos()
    {
        return $this->hasMany(ProjetPhoto::class)->orderBy('ordre');
    }

    public function photoPrincipale()
    {
        return $this->hasOne(ProjetPhoto::class)->where('principale', true)->orderBy('ordre');
    }

    // Retourne la première photo disponible (principale ou première de la liste)
    public function getPhotoCouvertureAttribute(): ?string
    {
        $principale = $this->photos->where('principale', true)->first();
        if ($principale) return asset('storage/' . $principale->path);

        $premiere = $this->photos->first();
        if ($premiere) return asset('storage/' . $premiere->path);

        // Fallback sur image_path (ancienne colonne)
        if ($this->image_path) return asset('storage/' . $this->image_path);

        return null;
    }
}
