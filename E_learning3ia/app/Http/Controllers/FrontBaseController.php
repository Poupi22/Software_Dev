<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Contact; // N'oublie pas d'importer Contact

class FrontBaseController extends Controller
{
    protected $itemsPerPage = 6;
    protected $upcomingEventsCount = 4;

    public function __construct()
    {
        // Partage automatique de la variable $contact dans toutes les vues
        view()->share('contact', Contact::first());
    }
    
    protected function applySearch($query, $search, $searchFields)
    {
        return $query->when($search, function($query) use ($search, $searchFields) {
            $query->where(function($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        });
    }
    
    protected function getUpcomingEvents()
    {
        return \App\Models\Evenement::where('statut', 'actif')
            ->where('date_debut', '>=', Carbon::now())
            ->orderBy('date_debut', 'asc')
            ->take($this->upcomingEventsCount)
            ->get();
    }
}
