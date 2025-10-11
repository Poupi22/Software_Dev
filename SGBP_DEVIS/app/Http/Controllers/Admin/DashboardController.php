<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Prospect;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clients' => Client::count(),
            'devis' => Devis::count(),
            'devis_acceptes' => Devis::where('statut', 'accepte')->count(),
            'factures' => Facture::count(),
            'factures_payees' => Facture::where('statut_paiement', 'paye')->count(),
            'prospects' => Prospect::where('statut', 'nouveau')->count(),
            'ca_total' => Facture::where('statut_paiement', 'paye')->sum('total_ttc'),
            'ca_en_attente' => Facture::where('statut_paiement', '!=', 'paye')->sum('total_ttc'),
        ];
        
        $devis_recents = Devis::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $factures_recentes = Facture::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact('stats', 'devis_recents', 'factures_recentes'));
    }
}
