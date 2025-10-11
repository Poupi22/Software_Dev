<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pv extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'titre',
        'facture_id',
        'client_id',
        'date_reception',
        'lieu_reception',
        'description_travaux',
        'observations',
        'reserves',
        'etat_travaux',
        'signature_client_path',
        'signature_entreprise_path',
        'date_signature_client',
        'date_signature_entreprise',
        'statut',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_reception' => 'date',
        'date_signature_client' => 'datetime',
        'date_signature_entreprise' => 'datetime',
    ];

    // Auto-generate numero
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pv) {
            if (empty($pv->numero)) {
                $pv->numero = self::generateNumero();
            }
        });
    }

    public static function generateNumero()
    {
        $annee = date('Y');
        $dernier = self::whereYear('created_at', $annee)->count();
        return sprintf('PV-%s-%03d', $annee, $dernier + 1);
    }

    // Relations
    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
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
    public function scopeSignes($query)
    {
        return $query->where('statut', 'signe');
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }
}

