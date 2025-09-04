<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'date_debut',
        'date_fin',
        'type_evenement',
        'image',
        'statut'
    ];

    protected $dates = ['date_debut', 'date_fin'];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'actif' => 'Actif',
        'archive' => 'Archivé'
    ];
    // app/Models/Evenement.php

protected $casts = [
    'date_debut' => 'datetime',
    'date_fin' => 'datetime',
];

}