<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Temoignage;
use Illuminate\Http\Request;

class FrontActualiteController extends FrontBaseController  // <-- ici on étend FrontBaseController
{
    public function index()
    {
        try {
            $search = request('search');
            $perPage = 6;
            $testimonialCount = 3;

            // Get paginated actualites with search
            $actualites = Actualite::query()
                ->when($search, function($query) use ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('titre', 'like', "%{$search}%")
                          ->orWhere('contenu', 'like', "%{$search}%");
                    });
                })
                ->orderBy('date_publication', 'desc')
                ->paginate($perPage)
                ->withQueryString(); // Preserve search parameters in pagination

            // Get latest testimonials
        $temoignages = Temoignage::where('publie', true)
                                 ->latest()
                                 ->take(10)
                                 ->get();

            return view('acceuil.actualite', compact('actualites', 'temoignages'));

        } catch (\Exception $e) {
            \Log::error('Error in FrontActualiteController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des actualités.');
        }
    }
}
