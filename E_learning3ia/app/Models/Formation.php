<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code', 'description','image'];

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }

      public function qualifications(): BelongsToMany
    {
        return $this->belongsToMany(Qualification::class, 'programmes', 'formation_id', 'qualification_id');
    }

}

