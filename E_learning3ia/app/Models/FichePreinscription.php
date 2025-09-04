<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichePreinscription extends Model
{
    use HasFactory;
    protected $fillable = ['chemin_fichier', 'nom_original'];
}
