<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactureCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'facture_id',
        'nom',
        'ordre',
        'main_oeuvre',
    ];

    protected $casts = [
        'main_oeuvre' => 'decimal:2',
    ];

    // Relations
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function articles()
    {
        return $this->hasMany(FactureArticle::class);
    }
}

