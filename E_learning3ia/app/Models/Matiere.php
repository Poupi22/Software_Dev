<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'user_id', 'code', 'credit'];

    protected $casts = [
        'credit' => 'integer',
    ];

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class)->orderBy('ordre');
    }

    public function quiz() {
        return $this->morphOne(Quiz::class, 'quizzable');
    }

    public function coursInstances(): HasMany
    {
        return $this->hasMany(CoursInstance::class);
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    // AJOUTEZ CETTE RELATION MANQUANTE
    public function programmes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class, 'programme_matieres')
                    ->withPivot('trimestre');
    }
}