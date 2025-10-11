<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'nom',
        'reference',
        'description',
        'unite',
        'prix_ht',
        'prix_modifiable',
        'actif',
        'gestion_stock',
        'stock_actuel',
        'stock_alerte',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'prix_ht' => 'decimal:2',
        'prix_modifiable' => 'boolean',
        'actif' => 'boolean',
        'gestion_stock' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->reference)) {
                $article->reference = self::generateReference();
            }
        });
    }

    private static function generateReference()
    {
        $year = date('Y');
        $prefix = 'ART-' . $year . '-';
        $prefixLen = strlen($prefix) + 1;

        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        if ($driver === 'sqlite') {
            $castExpr = "MAX(CAST(SUBSTR(reference, {$prefixLen}) AS INTEGER))";
        } else {
            $castExpr = "MAX(CAST(SUBSTRING(reference, {$prefixLen}) AS UNSIGNED))";
        }

        $lastNum = (int) self::withTrashed()
            ->where('reference', 'like', $prefix . '%')
            ->selectRaw("{$castExpr} as max_num")
            ->value('max_num');

        do {
            $lastNum++;
            $reference = 'ART-' . $year . '-' . str_pad($lastNum, 4, '0', STR_PAD_LEFT);
        } while (self::withTrashed()->where('reference', $reference)->exists());

        return $reference;
    }

    // Accessors
    public function getPrixTtcAttribute()
    {
        try {
            $parametre = \App\Models\Parametre::get();
            $tva = ($parametre->tva_fcfa ?? 19.25) / 100;
        } catch (\Throwable $e) {
            $tva = 0.1925;
        }
        return $this->prix_ht * (1 + $tva);
    }

    public function getTypeDisplayAttribute()
    {
        return $this->type === 'produit' ? 'Produit' : 'Service';
    }

    // Relations
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function devisArticles()
    {
        return $this->hasMany(DevisArticle::class);
    }

    public function factureArticles()
    {
        return $this->hasMany(FactureArticle::class);
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
    public function scopeProduits($query)
    {
        return $query->where('type', 'produit');
    }

    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEnRupture($query)
    {
        return $query->where('gestion_stock', true)
                     ->whereColumn('stock_actuel', '<=', 'stock_alerte');
    }


}
