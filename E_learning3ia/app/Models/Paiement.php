<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['inscription_id', 'montant'];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }
}
