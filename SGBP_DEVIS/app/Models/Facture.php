<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facture extends Model
{
    use HasFactory, SoftDeletes;

    // Seuil TPS au Gabon (en FCFA)
    const SEUIL_TPS_HAUT = 60000000;
    const TAUX_TPS_BAS   = 9.50;  // < 60 000 000
    const TAUX_TPS_HAUT  = 18.00; // >= 60 000 000
    const TAUX_CSS       = 1.00;

    protected $fillable = [
        'numero',
        'titre',
        'client_id',
        'devis_id',
        'type',
        'devise',
        'date_emission',
        'date_echeance',
        'introduction',
        'conclusion',
        'conditions_paiement',
        'total_ht',
        'taux_tps',
        'total_tps',
        'taux_css',
        'total_css',
        'main_oeuvre',
        'total_ttc',
        'statut_paiement',
        'montant_paye',
        'statut',
        'date_envoi',
        'pv_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_emission'  => 'date',
        'date_echeance'  => 'date',
        'taux_tps'       => 'decimal:2',
        'total_tps'      => 'decimal:2',
        'taux_css'       => 'decimal:2',
        'total_css'      => 'decimal:2',
        'total_ht'       => 'decimal:2',
        'main_oeuvre'    => 'decimal:2',
        'total_ttc'      => 'decimal:2',
        'montant_paye'   => 'decimal:2',
        'date_envoi'     => 'datetime',
    ];

    // Auto-generate numero
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($facture) {
            if (empty($facture->numero)) {
                $facture->numero = self::generateNumero();
            }
        });
    }

    public static function generateNumero(): string
    {
        $annee  = date('Y');
        $prefix = 'FACT-' . $annee . '-';

        // Trouver le dernier numéro utilisé pour cette année
        $dernier = self::where('numero', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(numero, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->value('numero');

        if ($dernier) {
            $lastNum = (int) substr($dernier, strlen($prefix));
        } else {
            $lastNum = 0;
        }

        // Incrémenter jusqu'à trouver un numéro libre (sécurité anti-collision)
        do {
            $lastNum++;
            $numero = sprintf('FACT-%s-%03d', $annee, $lastNum);
        } while (self::where('numero', $numero)->exists());

        return $numero;
    }

    /**
     * Calcule le taux TPS selon le montant HT
     */
    public static function calculerTauxTps(float $totalHt): float
    {
        return $totalHt >= self::SEUIL_TPS_HAUT ? self::TAUX_TPS_HAUT : self::TAUX_TPS_BAS;
    }

    // Accessors
    public function getResteAPayerAttribute()
    {
        return $this->total_ttc - $this->montant_paye;
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function categories()
    {
        return $this->hasMany(FactureCategory::class);
    }

    public function articles()
    {
        return $this->hasMany(FactureArticle::class);
    }

    public function pv()
    {
        return $this->belongsTo(Pv::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeNonPayees($query)
    {
        return $query->where('statut_paiement', 'non_paye');
    }

    public function scopePayees($query)
    {
        return $query->where('statut_paiement', 'paye');
    }

    // Méthodes
    public function calculerTotaux()
    {
        $this->load('articles', 'categories');

        $this->total_ht  = $this->articles->sum('total_ht');
        $totalMainOeuvre = $this->main_oeuvre + $this->categories->sum('main_oeuvre');

        // TPS = 9,5% de la main d'œuvre uniquement
        $this->taux_tps  = self::TAUX_TPS_BAS; // 9,5% fixe sur la main d'œuvre
        $this->total_tps = round($totalMainOeuvre * $this->taux_tps / 100, 2);

        // CSS = 1% du total HT (inchangé)
        $this->taux_css  = self::TAUX_CSS;
        $this->total_css = round($this->total_ht * self::TAUX_CSS / 100, 2);

        $this->total_ttc = $this->total_ht + $totalMainOeuvre + $this->total_tps + $this->total_css;
        $this->save();
    }
}
