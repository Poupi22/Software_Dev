<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progression extends Model {
    use HasFactory;
    
    protected $table = 'progressions';
    protected $fillable = ['user_id', 'lecon_id', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la leçon
     */
    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class);
    }
}