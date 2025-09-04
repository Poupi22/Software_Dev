<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursInstance extends Model
{
    use HasFactory;
    protected $fillable = ['programme_session_id', 'matiere_id', 'trimestre', 'statut'];

    public function programmeSession() {
        return $this->belongsTo(ProgrammeSession::class);
    }
    
    public function matiere() {
        return $this->belongsTo(Matiere::class);
    }
    
    public function formateurs() {
        return $this->belongsToMany(User::class, 'cours_instance_formateur', 'cours_instance_id', 'user_id')
                    ->withPivot('role_pedagogique')->withTimestamps();
    }

    /**
     * Relation avec les notes des étudiants pour ce cours
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    
    /**
     * Relation avec les chapitres via la matière
     */
    public function chapitres()
    {
        return $this->hasManyThrough(
            Chapitre::class,        // Modèle cible
            Matiere::class,         // Modèle intermédiaire
            'id',                   // Clé étrangère sur le modèle intermédiaire (Matiere)
            'matiere_id',           // Clé étrangère sur le modèle cible (Chapitre)
            'matiere_id',           // Clé locale sur ce modèle (CoursInstance)
            'id'                    // Clé locale sur le modèle intermédiaire (Matiere)
        );
    }
    
}