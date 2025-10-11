<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'nom',
        'ordre',
        'main_oeuvre',
        'main_oeuvre_pourcentage',
    ];

    protected $casts = [
        'main_oeuvre'             => 'decimal:2',
        'main_oeuvre_pourcentage' => 'decimal:2',
    ];

    // Relations
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function articles()
    {
        return $this->hasMany(DevisArticle::class);
    }
}

