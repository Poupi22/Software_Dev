<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assiduite extends Model
{
    use HasFactory;

    protected $table = 'assiduite';

    protected $fillable = [
        'user_id',
        'programme_session_id',
        'semestre',
        'pourcentage_presence',
    ];

    protected $casts = [
        'semestre' => 'integer',
        'pourcentage_presence' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur (étudiant)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la session de programme
     */
    public function programmeSession(): BelongsTo
    {
        return $this->belongsTo(ProgrammeSession::class);
    }

    /**
     * Obtenir le pourcentage d'absence
     */
    public function getPourcentageAbsenceAttribute(): float
    {
        return 100 - $this->pourcentage_presence;
    }
}
