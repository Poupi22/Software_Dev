<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model {
    use HasFactory;
    protected $fillable = ['titre', 'quizzable_id', 'quizzable_type', 'description', 'seuil_reussite','duree_minutes'];
    public function quizzable() {
        return $this->morphTo();
    }
    public function questions() {
        return $this->hasMany(Question::class);
    }
    public function tentatives(): HasMany
    {
        return $this->hasMany(QuizTentative::class, 'quiz_id', 'id');
    }
}
