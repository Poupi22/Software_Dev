<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    use HasFactory;

    protected $fillable = ['lecon_id', 'titre', 'type', 'contenu', 'ordre'];

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}
