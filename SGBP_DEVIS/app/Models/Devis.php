<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Devis extends Model
{
    use HasFactory, SoftDeletes;

    const SEUIL_TPS_HAUT = 60000000;
    const TAUX_TPS_BAS   = 9.50;
    const TAUX_TPS_HAUT  = 18.00;
    const TAUX_CSS       = 1.00;

    protected $fillable = [
        'numero',
        'titre',
        'client_id',
        'type',
        'devise',
        'validite_mois',
        'introduction',
        'conclusion',
        'total_ht',
        'taux_tps',
        'total_tps',
        'taux_css',
        'total_css',
        'main_oeuvre',
        'main_oeuvre_pourcentage',
        'total_ttc',
        'statut',
        'date_envoi',
        'date_acceptation',
        'facture_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'validite_mois'           => 'integer',
        'taux_tps'                => 'decimal:2',
        'total_tps'               => 'decimal:2',
        'taux_css'                => 'decimal:2',
        'total_css'               => 'decimal:2',
        'total_ht'                => 'decimal:2',
        'main_oeuvre'             => 'decimal:2',
        'main_oeuvre_pourcentage' => 'decimal:2',
        'total_ttc'               => 'decimal:2',
        'date_envoi'              => 'datetime',
        'date_acceptation'        => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($devis) {
            if (empty($devis->numero)) {
                $devis->numero = self::generateNumero();
            }
        });
    }

    public static function generateNumero(): string
    {
        $annee  = date('Y');
        $prefix = 'DEV-' . $annee . '-';
        $prefixLen = strlen($prefix) + 1;

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $castExpr = "MAX(CAST(SUBSTR(numero, {$prefixLen}) AS INTEGER))";
        } else {
            $castExpr = "MAX(CAST(SUBSTRING(numero, {$prefixLen}) AS UNSIGNED))";
        }

        $lastNum = (int) self::withTrashed()
            ->where('numero', 'like', $prefix . '%')
            ->selectRaw("{$castExpr} as max_num")
            ->value('max_num');

        do {
            $lastNum++;
            $numero = sprintf('DEV-%s-%03d', $annee, $lastNum);
        } while (self::withTrashed()->where('numero', $numero)->exists());

        return $numero;
    }

    public static function calculerTauxTps(float $totalHt): float
    {
        return $totalHt >= self::SEUIL_TPS_HAUT ? self::TAUX_TPS_HAUT : self::TAUX_TPS_BAS;
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function categories()
    {
        return $this->hasMany(DevisCategory::class);
    }

    public function articles()
    {
        return $this->hasMany(DevisArticle::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
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
    public function scopeProvisoires($query)
    {
        return $query->where('type', 'provisoire');
    }

    public function scopeFinaux($query)
    {
        return $query->where('type', 'final');
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeEnvoyes($query)
    {
        return $query->where('statut', 'envoye');
    }

    public function scopeAcceptes($query)
    {
        return $query->where('statut', 'accepte');
    }

    // Méthodes
    public function calculerTotaux()
    {
        $this->load('articles', 'categories');

        $this->total_ht = $this->articles->sum('total_ht');

        // Main d'œuvre hors catégorie : montant fixe ou pourcentage du total HT
        if ($this->main_oeuvre_pourcentage) {
            $this->main_oeuvre = round($this->total_ht * $this->main_oeuvre_pourcentage / 100, 2);
        }

        // Main d'œuvre par catégorie : montant fixe ou pourcentage du sous-total de la catégorie
        $totalMainOeuvreCategories = 0;
        foreach ($this->categories as $category) {
            if ($category->main_oeuvre_pourcentage) {
                $sousTotalCat = $category->articles->sum('total_ht');
                $category->main_oeuvre = round($sousTotalCat * $category->main_oeuvre_pourcentage / 100, 2);
                $category->save();
            }
            $totalMainOeuvreCategories += $category->main_oeuvre;
        }

        $totalMainOeuvre = $this->main_oeuvre + $totalMainOeuvreCategories;

        // TPS = 9,5% de la main d'œuvre uniquement
        $this->taux_tps  = self::TAUX_TPS_BAS;
        $this->total_tps = round($totalMainOeuvre * $this->taux_tps / 100, 2);

        // CSS = 1% du total HT
        $this->taux_css  = self::TAUX_CSS;
        $this->total_css = round($this->total_ht * self::TAUX_CSS / 100, 2);

        $this->total_ttc = $this->total_ht + $totalMainOeuvre + $this->total_tps + $this->total_css;
        $this->save();
    }

    public function bonCommande()
{
    return $this->hasOne(BonCommande::class);
}
}