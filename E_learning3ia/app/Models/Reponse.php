<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reponse extends Model {
    use HasFactory;
    protected $fillable = ['question_id', 'texte', 'est_correcte'];
    protected $casts = ['est_correcte' => 'boolean'];
}
