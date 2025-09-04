<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model {
    use HasFactory;
    protected $fillable = ['quiz_id', 'enonce'];
    public function reponses() { return $this->hasMany(Reponse::class); }
}
