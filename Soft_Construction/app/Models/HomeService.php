<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'feature_title_1',
        'feature_description_1',
        'feature_icon_1',
        'feature_title_2',
        'feature_description_2',
        'feature_icon_2',
        'feature_title_3',
        'feature_description_3',
        'feature_icon_3',
        'button_text',
        'order',
        'is_active'
    ];
}