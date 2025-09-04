<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenuAdditionnel extends Model
{
    use HasFactory;
    protected $fillable = ['programme_session_id', 'user_id', 'titre', 'description', 'est_visible'];

    public function ressources() {
        return $this->hasMany(RessourceAdditionnelle::class)->orderBy('ordre');
    }
}
