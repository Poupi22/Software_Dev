<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\HomeSlide;
use App\Models\Personnel;
use App\Models\Project;
use App\Models\Training;
use Illuminate\Http\Request;

class FrontAboutController extends Controller
{
    public function index()
    {
        // Get about data - assuming you want to display the first one
        $about = About::first();
        
        // Get home slides for the hero section
        $slides = HomeSlide::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get leadership team (active personnel)
        $leadershipTeam = Personnel::where('is_active', true)
            ->orderBy('order')
            ->limit(4)
            ->get();
            
        // Get statistics for the counter
        $stats = [
            'trained_people' => Training::count() * 20, // Example calculation
            'completed_projects' => Project::where('is_featured', true)->count(),
            'regions' => 3, // Hardcoded as per your design
            'employees' => Personnel::count()
        ];
        
        return view('home.about', compact('about', 'slides', 'leadershipTeam', 'stats'));
    }
}