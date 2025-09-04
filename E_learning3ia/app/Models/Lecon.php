<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecon extends Model
{
    use HasFactory;
    protected $fillable = ['chapitre_id', 'titre', 'ordre'];

    public function chapitre()
    {
        return $this->belongsTo(Chapitre::class);
    }

    public function ressources()
    {
        return $this->hasMany(Ressource::class)->orderBy('ordre');
    }

    public function quiz() {
        return $this->morphOne(Quiz::class, 'quizzable');
    }
    public function isCompleted()
{
    // Temporairement retourner false en attendant l'implémentation du système de progression
    return false;
}
}
