<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    use HasFactory;

    protected $fillable = ['matiere_id', 'nom', 'ordre'];

    /**
     * Relation avec les leçons
     */
    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class)->orderBy('ordre');
    }

    /**
     * Relation avec la matière
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation avec les instances de cours via la matière
     * (Relation indirecte)
     */
    public function coursInstances()
    {
        return $this->hasManyThrough(
            CoursInstance::class,
            Matiere::class,
            'id', // Clé étrangère sur la table matières
            'matiere_id', // Clé étrangère sur la table cours_instances
            'matiere_id', // Clé locale sur la table chapitres
            'id' // Clé locale sur la table matières
        );
    }

    /**
     * Relation avec les sessions de programme via la matière et les instances de cours
     * (Relation indirecte)
     */
    public function programmeSessions()
    {
        return $this->hasManyThrough(
            ProgrammeSession::class,
            CoursInstance::class,
            'matiere_id', // Clé étrangère sur la table cours_instances
            'id', // Clé étrangère sur la table programme_sessions
            'matiere_id', // Clé locale sur la table chapitres
            'programme_session_id' // Clé locale sur la table cours_instances
        );
    }

    /**
     * Scope pour charger toutes les relations couramment utilisées
     */
    public function scopeWithAllRelations($query)
    {
        return $query->with([
            'matiere',
            'lecons',
            'lecons.ressources',
            'matiere.coursInstances',
            'matiere.coursInstances.programmeSession',
            'matiere.coursInstances.formateurs'
        ]);
    }

    /**
     * Accesseur pour le nom complet du chapitre
     */
    public function getNomCompletAttribute(): string
    {
        return "Chapitre {$this->ordre}: {$this->nom}";
    }

    /**
     * Vérifie si le chapitre a des leçons
     */
    public function hasLecons(): bool
    {
        return $this->lecons()->exists();
    }

    /**
     * Compte le nombre de leçons dans le chapitre
     */
    public function countLecons(): int
    {
        return $this->lecons()->count();
    }

    /**
     * Récupère la prochaine leçon dans l'ordre
     */
    public function nextLecon(Lecon $currentLecon): ?Lecon
    {
        return $this->lecons()
            ->where('ordre', '>', $currentLecon->ordre)
            ->orderBy('ordre')
            ->first();
    }
    // Dans app/Models/Chapitre.php
public function quiz()
{
    return $this->morphOne(Quiz::class, 'quizzable');
}



    /**
     * Récupère la leçon précédente dans l'ordre
     */
    public function previousLecon(Lecon $currentLecon): ?Lecon
    {
        return $this->lecons()
            ->where('ordre', '<', $currentLecon->ordre)
            ->orderByDesc('ordre')
            ->first();
    }
    
}