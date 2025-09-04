<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'adresse',
        'telephone',
        'email',
        'whatsapp',
        'iframe_localisation',
        'facebook_link',
        'tiktok_link',
        'linkedin_link'
    ];
}