<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'qualification_id',
        'prix',
        'duree',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function qualification(): BelongsTo
    {
        return $this->belongsTo(Qualification::class);
    }

    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'programme_matieres')
                    ->withPivot('trimestre');
    }

    public function sessions()
    {
        return $this->hasMany(ProgrammeSession::class);
    }

    public function matieresPivot(): HasMany
    {
        return $this->hasMany(ProgrammeMatiere::class);
    }
}
