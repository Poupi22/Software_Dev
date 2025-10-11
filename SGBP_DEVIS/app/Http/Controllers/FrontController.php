<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use App\Models\Prospect;
use App\Models\Service;
use App\Models\Projet;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function welcome()
    {
        $services = Service::actifs()->take(6)->get();
        $projets  = Projet::actifs()->with('photos')->take(6)->get();

        return view('welcome', compact('services', 'projets'));
    }

    public function projets()
    {
        $projets   = Projet::actifs()->with('photos')->get();
        $parametre = Parametre::get();

        return view('projet', compact('projets', 'parametre'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:30',
            'entreprise' => 'nullable|string|max:255',
            'objet' => 'required|string|max:255',
            'message' => 'required|string|min:20',
        ]);

        Prospect::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'] ?? null,
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'entreprise' => $validated['entreprise'] ?? null,
            'objet' => $validated['objet'],
            'message' => $validated['message'],
            'source' => 'site_web',
            'page_origine' => url()->previous(),
            'ip_address' => $request->ip(),
            'statut' => 'nouveau',
            'date_premier_contact' => now(),
        ]);

        return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}
