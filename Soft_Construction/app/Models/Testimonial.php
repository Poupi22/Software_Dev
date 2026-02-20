<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'content',
        'rating',
        'avatar',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helper method to get full star count
    public function fullStars()
    {
        return floor($this->rating);
    }

    // Helper method to check if there's a half star
    public function hasHalfStar()
    {
        return ($this->rating - floor($this->rating)) >= 0.5;
    }
}