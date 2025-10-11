<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevisArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'devis_category_id',
        'article_id',
        'designation',
        'unite',
        'quantite',
        'prix_unitaire_ht',
        'remise_pourcentage',
        'ordre',
        'montant_remise',
        'total_ht',
        'total_tva',
        'total_ttc',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire_ht' => 'decimal:2',
        'remise_pourcentage' => 'decimal:2',
        'montant_remise' => 'decimal:2',
        'total_ht' => 'decimal:2',
        'total_tva' => 'decimal:2',
        'total_ttc' => 'decimal:2',
    ];

    // Auto-calculate totals
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($devisArticle) {
            $devisArticle->calculerTotaux();
        });
    }

    public function calculerTotaux()
    {
        $montant_brut = $this->quantite * $this->prix_unitaire_ht;
        $this->montant_remise = $montant_brut * ($this->remise_pourcentage / 100);
        $this->total_ht = $montant_brut - $this->montant_remise;
        
        $devis = $this->devis;
        if ($devis && $devis->appliquer_tva) {
            $this->total_tva = $this->total_ht * ($devis->taux_tva / 100);
        } else {
            $this->total_tva = 0;
        }
        
        $this->total_ttc = $this->total_ht + $this->total_tva;
    }

    // Relations
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function category()
    {
        return $this->belongsTo(DevisCategory::class, 'devis_category_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}

