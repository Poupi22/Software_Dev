<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'formation_id'];

    // Relation avec la formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    // Relation avec l'administrateur créateur
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Génération automatique du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($forum) {
            $forum->slug = Str::slug($forum->name);
        });
    }
       public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}