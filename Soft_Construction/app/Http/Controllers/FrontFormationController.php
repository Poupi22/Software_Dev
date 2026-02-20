<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\Training;
use Illuminate\Http\Request;

class FrontFormationController extends Controller
{
    public function index()
    {
        // Get home slides for the hero section
        $slides = HomeSlide::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get active training programs
        $trainings = Training::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('home.formation', compact('slides', 'trainings'));
    }
    
   
}