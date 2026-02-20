<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\Project;
use Illuminate\Http\Request;

class FrontProjetController extends Controller
{
    public function index()
    {
        // Get home slides for the hero section
        $slides = HomeSlide::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get featured projects
        $featuredProjects = Project::where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get all projects grouped by region
        $projectsByRegion = Project::where('is_featured', true)
            ->orderBy('region')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('region');
        
        // Get all unique regions for filtering
        $regions = Project::select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');
        
        return view('home.projet', compact('slides', 'featuredProjects', 'projectsByRegion', 'regions'));
    }
    
}