<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [];

    /**
     * Les utilisateurs qui participent à cette conversation.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
                    ->withTimestamps()
                    ->withPivot('last_read_at');
    }

    /**
     * Les messages de cette conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    
}
