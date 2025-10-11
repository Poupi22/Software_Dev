<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        // Particulier
        'nom',
        'prenom',
        // Société
        'raison_sociale',
        'bp',
        'nif',
        'rccm',
        'representant_legal',
        'fonction_representant',
        'secteur_activite',
        'site_web',
        // Commun
        'email',
        'telephone_principal',
        'telephone_secondaire',
        'adresse',
        'ville',
        'pays',
        'notes',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Accessors
    public function getNomCompletAttribute()
    {
        if ($this->type === 'particulier') {
            return "{$this->prenom} {$this->nom}";
        }
        return $this->raison_sociale;
    }

    public function getTypeDisplayAttribute()
    {
        return $this->type === 'particulier' ? 'Particulier' : 'Société';
    }

    // Relations
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function pvs()
    {
        return $this->hasMany(Pv::class);
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
    public function scopeParticuliers($query)
    {
        return $query->where('type', 'particulier');
    }

    public function scopeSocietes($query)
    {
        return $query->where('type', 'societe');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}