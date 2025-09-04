<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Ensure this is imported for Str helper usage

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'thread_id'];
    
    protected $with = ['user'];
    protected $appends = ['short_body'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getShortBodyAttribute()
    {
        return Str::limit($this->body, 50);
    }
}