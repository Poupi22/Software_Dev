<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image1',
        'image2',
        'image3',
        'order',
        'is_active'
    ];

    // Get all active slides ordered by their order value
    public static function activeSlides()
    {
        return self::where('is_active', true)->orderBy('order')->get();
    }
}