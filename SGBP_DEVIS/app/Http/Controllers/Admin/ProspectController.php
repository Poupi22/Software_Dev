<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Models\Client;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with('assignedTo', 'client');

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('entreprise', 'like', "%{$search}%")
                  ->orWhere('objet', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        match ($sort) {
            'ancien' => $query->oldest(),
            'nom' => $query->orderBy('nom'),
            'statut' => $query->orderBy('statut'),
            default => $query->latest(),
        };

        $prospects = $query->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => Prospect::count(),
            'nouveaux' => Prospect::where('statut', 'nouveau')->count(),
            'contactes' => Prospect::where('statut', 'contacte')->count(),
            'qualifies' => Prospect::where('statut', 'qualifie')->count(),
            'convertis' => Prospect::where('statut', 'converti')->count(),
            'perdus' => Prospect::where('statut', 'perdu')->count(),
        ];
        $stats['taux_conversion'] = $stats['total'] > 0
            ? round(($stats['convertis'] / $stats['total']) * 100)
            : 0;

        return view('admin.prospects.index', compact('prospects', 'stats'));
    }

    public function show(Prospect $prospect)
    {
        $prospect->load('assignedTo', 'client');
        return view('admin.prospects.show', compact('prospect'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'statut' => 'required|in:nouveau,contacte,qualifie,converti,perdu',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['statut'] === 'contacte' && !$prospect->date_premier_contact) {
            $validated['date_premier_contact'] = now();
        }

        $prospect->update($validated);

        return back()->with('success', 'Prospect mis à jour avec succès !');
    }

    public function destroy(Prospect $prospect)
    {
        $prospect->delete();

        return redirect()->route('admin.prospects.index')
            ->with('success', 'Prospect supprimé avec succès !');
    }

    public function convertToClient(Prospect $prospect)
    {
        if ($prospect->statut === 'converti') {
            return back()->with('error', 'Ce prospect a déjà été converti.');
        }

        $isEntreprise = !empty($prospect->entreprise);

        $clientData = [
            'type' => $isEntreprise ? 'entreprise' : 'particulier',
            'nom' => $prospect->nom,
            'prenom' => $prospect->prenom ?? '',
            'email' => $prospect->email,
            'telephone_principal' => $prospect->telephone ?? 'À renseigner',
            'notes' => $prospect->message,
            'created_by' => auth()->id(),
        ];

        if ($isEntreprise) {
            $clientData['raison_sociale'] = $prospect->entreprise;
        }

        $client = Client::create($clientData);

        $prospect->update([
            'statut' => 'converti',
            'client_id' => $client->id,
        ]);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Prospect converti en client avec succès !');
    }
}
