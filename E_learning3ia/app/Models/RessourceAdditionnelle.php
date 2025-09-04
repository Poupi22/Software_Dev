<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceAdditionnelle extends Model
{
    use HasFactory;
    protected $fillable = ['contenu_additionnel_id', 'titre', 'type', 'contenu', 'ordre'];
}
