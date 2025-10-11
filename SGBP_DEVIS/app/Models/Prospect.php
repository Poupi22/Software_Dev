<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'entreprise',
        'objet',
        'message',
        'source',
        'page_origine',
        'ip_address',
        'statut',
        'client_id',
        'date_premier_contact',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'date_premier_contact' => 'datetime',
    ];

    // Accessors
    public function getNomCompletAttribute()
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeNouveaux($query)
    {
        return $query->where('statut', 'nouveau');
    }

    public function scopeQualifies($query)
    {
        return $query->where('statut', 'qualifie');
    }

    public function scopeConvertis($query)
    {
        return $query->where('statut', 'converti');
    }
}
