<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCommande extends Model
{
    protected $table = 'bons_commande';

    protected $fillable = [
        'devis_id',
        'numero',
        'fichier_path',
        'fichier_nom',
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }
}