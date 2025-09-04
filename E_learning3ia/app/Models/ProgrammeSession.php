<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeSession extends Model
{
    use HasFactory;
    protected $fillable = ['programme_id', 'annee_academique_id', 'statut'];

    public function programme() {
        return $this->belongsTo(Programme::class);
    }
    public function anneeAcademique() {
        return $this->belongsTo(AnneeAcademique::class);
    }
    public function coursInstances() {
        return $this->hasMany(CoursInstance::class);
    }
    public function contenusAdditionnels() {
        return $this->hasMany(ContenuAdditionnel::class);
    }
    public function inscriptions() {
        return $this->hasMany(Inscription::class);
    }
    public function formation()
{
    return $this->belongsTo(Formation::class, 'formation_id');
}

}
