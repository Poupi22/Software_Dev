<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizTentative extends Model {
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'quiz_id', 
        'score_obtenu', 
        'statut',
        'started_at',
        'submitted_at',
        'questions_selected' // ← ADD THIS LINE
    ];
    
    protected $casts = [
        'questions_selected' => 'array', // ← ADD THIS TO CAST JSON TO ARRAY
        'started_at' => 'datetime',
        'submitted_at' => 'datetime'
    ];
    
    public function reponsesChoisies() {
        return $this->belongsToMany(Reponse::class, 'tentative_reponses');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}