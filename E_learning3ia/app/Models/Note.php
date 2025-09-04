<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cours_instance_id',
        'note_cc',
        'note_normale',
        'note_quiz',
    ];

    protected $casts = [
        'note_cc' => 'decimal:2',
        'note_normale' => 'decimal:2',
        'note_quiz' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur (étudiant)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le cours instance
     */
    public function coursInstance(): BelongsTo
    {
        return $this->belongsTo(CoursInstance::class);
    }

    /**
     * Calculer la note finale de la matière
     * Phase 1: 70% Normale + 30% CC
     * Phase 2: 56% Normale + 24% CC + 20% Quiz
     */
    public function getNoteFinalAttribute(): ?float
    {
        if ($this->note_cc === null && $this->note_normale === null) {
            return null;
        }

        $includeQuiz = config('bulletin.include_quiz_online', false);
        
        $noteNormale = $this->note_normale ?? 0;
        $noteCc = $this->note_cc ?? 0;
        $noteQuiz = $this->note_quiz ?? 0;

        if ($includeQuiz && $this->note_quiz !== null) {
            // Phase 2 : Avec quiz
            $ponderation = config('bulletin.ponderation_phase2');
            return ($noteQuiz * $ponderation['quiz'] / 100) +
                   ($noteNormale * $ponderation['normale'] / 100) +
                   ($noteCc * $ponderation['cc'] / 100);
        } else {
            // Phase 1 : Sans quiz
            $ponderation = config('bulletin.ponderation_phase1');
            return ($noteNormale * $ponderation['normale'] / 100) +
                   ($noteCc * $ponderation['cc'] / 100);
        }
    }

    /**
     * Obtenir le semestre via le cours instance
     */
    public function getSemestreAttribute(): int
    {
        return $this->coursInstance->trimestre;
    }

    /**
     * Obtenir la matière via le cours instance
     */
    public function getMatiereAttribute()
    {
        return $this->coursInstance->matiere;
    }

    /**
     * Obtenir le crédit de la matière
     */
    public function getCreditAttribute(): int
    {
        return $this->coursInstance->matiere->credit ?? 1;
    }
}
