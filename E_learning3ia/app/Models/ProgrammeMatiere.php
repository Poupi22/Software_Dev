<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeMatiere extends Model
{
    use HasFactory;

    protected $table = 'programme_matieres';

    protected $fillable = ['programme_id', 'matiere_id', 'trimestre'];

    /**
     * Relation avec le modèle Programme
     */
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * Relation avec le modèle Matiere
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}