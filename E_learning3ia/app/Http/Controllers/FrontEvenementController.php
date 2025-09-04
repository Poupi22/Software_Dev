<?php

namespace App\Http\Controllers;

use App\Models\Evenement;

class FrontEvenementController extends FrontBaseController
{
    public function index()
    {
        $evenements = $this->getUpcomingEvents();
        return view('acceuil.index', compact('evenements'));
    }
    
    public function show($id)
    {
        $evenement = Evenement::findOrFail($id);
        $otherEvents = Evenement::where('statut', 'actif')
            ->where('id', '!=', $id)
            ->orderBy('date_debut', 'asc')
            ->take(3)
            ->get();
            
        return view('evenement.show', compact('evenement', 'otherEvents'));
    }
}