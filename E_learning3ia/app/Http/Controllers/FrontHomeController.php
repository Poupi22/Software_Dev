<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Evenement;
use App\Models\Temoignage;
use App\Models\Contact;
use App\Models\Accueil;
use App\Models\Formation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\FichePreinscription;
use App\Models\Programme;

class FrontHomeController extends Controller
{
    public function index()
    {
        // Récupération des événements actifs
        $evenements = Evenement::where('statut', 'actif')
            ->orderBy('date_debut', 'asc')
            ->paginate(10);


        // Récupération des témoignages
                $temoignages = Temoignage::where('publie', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

        // Informations de contact
        $contact = Contact::first();

        // Actualités récentes
        $actualites = Actualite::orderBy('date_publication', 'desc')
            ->take(4)
            ->get();

        // Images du carousel
        $carouselItems = Accueil::all();

        // Récupération des formations populaires (relationship removed)
       $programmes = Programme::whereHas('sessions', function ($query) {
            $query->where('statut', 'Ouverte aux inscriptions');
        })
        ->with(['formation', 'qualification'])
        ->get();



        return view('acceuil.index', compact(
            'evenements',
            'temoignages',
            'contact',
            'actualites',
            'carouselItems',
            'programmes'
        ));
    }
}
