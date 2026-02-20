<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\HomeSlide;
use App\Models\HomeService;
use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\Training;
use App\Models\Contact;
use App\Models\Personnel;
use Illuminate\Http\Request;

class FrontHomeController extends Controller
{
    public function index()
    {
        // Get all active data for the home page
        $slides = HomeSlide::where('is_active', true)->orderBy('order')->get();
        $about = About::first();
        $services = Service::where('active', true)->orderBy('order')->take(6)->get();
        $homeServices = HomeService::where('is_active', true)->orderBy('order')->get();
        $projects = Project::where('is_featured', true)->orderBy('created_at', 'desc')->take(6)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('created_at', 'desc')->get();
        $partners = Partner::where('is_active', true)->ordered()->get();
        $trainings = Training::where('is_active', true)->orderBy('order')->take(3)->get();
        $contact = Contact::first();
        $personnels = Personnel::where('is_active', true)->ordered()->take(4)->get();

        return view('home.home', compact(
            'slides',
            'about',
            'services',
            'homeServices',
            'projects',
            'testimonials',
            'partners',
            'trainings',
            'contact',
            'personnels'
        ));
    }
}