<?php

namespace App\Http\Controllers;

use App\Models\Temoignage;
use App\Models\About;

class FrontAboutController extends FrontBaseController
{
    public function index()
    {
        $about = About::first();
        $temoignages = Temoignage::where('publie', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        return view('acceuil.about', compact('about', 'temoignages'));
    }
}
