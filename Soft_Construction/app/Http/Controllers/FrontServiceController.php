<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use App\Models\Service;
use App\Models\HomeService;
use Illuminate\Http\Request;

class FrontServiceController extends Controller
{
    public function index()
    {
        // Get home slides for the hero section
        $slides = HomeSlide::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get main services (active services)
        $services = Service::where('active', true)
            ->orderBy('order')
            ->get();
            
        // Get home services features
        $homeServices = HomeService::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('home.service', compact('slides', 'services', 'homeServices'));
    }
    
 
}