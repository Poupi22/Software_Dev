<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Temoignage;
use App\Models\Contact;
use App\Models\Qualification; // Ajout de l'import
use Illuminate\Support\Str;
use App\Models\Programme;

class FrontFormationController extends FrontBaseController
{
    public function index()
    {
        $contact = Contact::first();
                $temoignages = Temoignage::where('publie', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        $programmes = Programme::whereHas('sessions', function ($query) {
            $query->where('statut', 'Ouverte aux inscriptions');
        })
        ->with(['formation', 'qualification'])
        ->get();

        $qualifications = Qualification::all();

        return view('acceuil.formation', compact('temoignages', 'contact', 'qualifications', 'programmes'));
    }

    public function show($slug)
    {
        // Find the formation by slug or fail
        $formation = Formation::where('nom', str_replace('-', ' ', $slug))->firstOrFail();

        // Return the view with the formation data
        return view('acceuil.formation-details', compact('formation'));
    }
}
