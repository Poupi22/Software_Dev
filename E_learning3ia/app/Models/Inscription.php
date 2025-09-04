<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'programme_session_id',
        'verse',
        'reste'
    ];

    protected $casts = [
        'verse' => 'integer',
        'reste' => 'integer',
    ];

    /**
     * Relation avec le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le modèle ProgrammeSession
     */
    public function programmeSession(): BelongsTo
    {
        return $this->belongsTo(ProgrammeSession::class);
    }

    /**
     * Relation avec le modèle Paiement
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Accéder à la formation via la session de programme et le programme
     */
    public function formation()
    {
        return $this->hasOneThrough(
            Formation::class, // Modèle cible
            Programme::class, // Modèle intermédiaire
            'id', // Clé étrangère sur Programme
            'id', // Clé étrangère sur Formation
            'programme_session_id', // Clé locale sur Inscription
            'formation_id' // Clé étrangère sur Programme
        );
    }
}