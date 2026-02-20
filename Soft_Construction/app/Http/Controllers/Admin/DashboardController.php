<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Training;
use App\Models\Project;
use App\Models\Service;
use App\Models\Personnel;
use App\Models\Partner;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts from database
        $testimonialsCount = Testimonial::count();
        $trainingsCount = Training::where('is_active', true)->count();
        $projectsCount = Project::count();
        $servicesCount = Service::count();
        $personnelCount = Personnel::count();
        $partnersCount = Partner::count();

        return view('admin.dashboard', compact(
            'testimonialsCount',
            'trainingsCount',
            'projectsCount',
            'servicesCount',
            'personnelCount',
            'partnersCount'
        ));
    }
}