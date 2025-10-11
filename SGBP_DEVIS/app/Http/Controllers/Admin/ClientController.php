<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = Client::with('createdBy');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('raison_sociale', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone_principal', 'like', "%{$search}%")
                  ->orWhere('nif', 'like', "%{$search}%")
                  ->orWhere('rccm', 'like', "%{$search}%");
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            if ($request->type === 'particulier') {
                $query->particuliers();
            } elseif ($request->type === 'societe') {
                $query->societes();
            }
        }

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'actif') {
                $query->actifs();
            } elseif ($request->status === 'inactif') {
                $query->where('actif', false);
            }
        }

        // Tri
        $sortField     = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        if (in_array($sortField, ['created_at', 'nom', 'raison_sociale'])) {
            if ($sortField === 'nom') {
                $query->orderByRaw('COALESCE(nom, raison_sociale) ' . $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        $clients = $query->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        if ($request->has('redirect')) {
            session(['client_redirect' => $request->redirect]);
        }
        return view('admin.clients.create');
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $rules = [
            'type'                  => 'required|in:particulier,societe',
            'telephone_principal'   => 'required|string|max:25',
            'telephone_secondaire'  => 'nullable|string|max:25',
            'adresse'               => 'nullable|string',
            'ville'                 => 'nullable|string|max:255',
            'pays'                  => 'nullable|string|max:5',
            'notes'                 => 'nullable|string',
            'actif'                 => 'boolean',
        ];

        // Validation conditionnelle selon le type
        if ($request->type === 'particulier') {
            $rules['nom']    = 'required|string|max:255';
            $rules['prenom'] = 'required|string|max:255';
            $rules['email']  = 'nullable|email|unique:clients,email|max:255';
        } else {
            $rules['raison_sociale']        = 'required|string|max:255';
            $rules['bp']                    = 'required|string|max:255';
            $rules['nif']                   = 'required|string|max:255';
            $rules['rccm']                  = 'nullable|string|max:100';
            $rules['representant_legal']    = 'nullable|string|max:255';
            $rules['fonction_representant'] = 'nullable|string|max:255';
            $rules['secteur_activite']      = 'nullable|string|max:255';
            $rules['site_web']              = 'nullable|url|max:255';
            $rules['email']                 = 'required|email|unique:clients,email|max:255';
        }

        $validated = $request->validate($rules, [
            'nom.required'            => 'Le nom est obligatoire.',
            'prenom.required'         => 'Le prénom est obligatoire.',
            'raison_sociale.required' => 'La raison sociale est obligatoire.',
            'bp.required'             => 'La boîte postale (BP) est obligatoire.',
            'nif.required'            => 'Le NIF/NIU est obligatoire.',
            'email.required'          => 'L\'email est obligatoire pour les sociétés.',
            'email.email'             => 'L\'email doit être valide.',
            'email.unique'            => 'Cet email est déjà utilisé.',
            'telephone_principal.required' => 'Le téléphone principal est obligatoire.',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['actif']      = $request->boolean('actif', true);

        $client  = Client::create($validated);
        $redirect = session('client_redirect');
        session()->forget('client_redirect');

        if ($redirect === 'devis.create') {
            return redirect()->route('admin.devis.create')
                ->with([
                    'success'        => 'Client créé avec succès !',
                    'client_created' => true,
                    'new_client_id'  => $client->id,
                ]);
        }

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client créé avec succès !');
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Client $client)
    {
        $client->load(['devis', 'factures', 'pvs', 'createdBy', 'updatedBy']);

        $caTotal = $client->factures()->where('statut_paiement', 'paye')->sum('total_ttc');

        return view('admin.clients.show', compact('client', 'caTotal'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $rules = [
            'type'                  => 'required|in:particulier,societe',
            'telephone_principal'   => 'required|string|max:25',
            'telephone_secondaire'  => 'nullable|string|max:25',
            'adresse'               => 'nullable|string',
            'ville'                 => 'nullable|string|max:255',
            'pays'                  => 'nullable|string|max:5',
            'notes'                 => 'nullable|string',
            'actif'                 => 'boolean',
        ];

        if ($request->type === 'particulier') {
            $rules['nom']    = 'required|string|max:255';
            $rules['prenom'] = 'required|string|max:255';
            $rules['email']  = 'nullable|email|unique:clients,email,' . $client->id . '|max:255';
        } else {
            $rules['raison_sociale']        = 'required|string|max:255';
            $rules['bp']                    = 'required|string|max:255';
            $rules['nif']                   = 'required|string|max:255';
            $rules['rccm']                  = 'nullable|string|max:100';
            $rules['representant_legal']    = 'nullable|string|max:255';
            $rules['fonction_representant'] = 'nullable|string|max:255';
            $rules['secteur_activite']      = 'nullable|string|max:255';
            $rules['site_web']              = 'nullable|url|max:255';
            $rules['email']                 = 'required|email|unique:clients,email,' . $client->id . '|max:255';
        }

        $validated = $request->validate($rules, [
            'nom.required'            => 'Le nom est obligatoire.',
            'prenom.required'         => 'Le prénom est obligatoire.',
            'raison_sociale.required' => 'La raison sociale est obligatoire.',
            'bp.required'             => 'La boîte postale (BP) est obligatoire.',
            'nif.required'            => 'Le NIF/NIU est obligatoire.',
            'email.required'          => 'L\'email est obligatoire pour les sociétés.',
            'email.email'             => 'L\'email doit être valide.',
            'email.unique'            => 'Cet email est déjà utilisé.',
            'telephone_principal.required' => 'Le téléphone principal est obligatoire.',
        ]);

        $validated['actif']      = $request->boolean('actif', true);
        $validated['updated_by'] = auth()->id();

        $client->update($validated);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client mis à jour avec succès !');
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        $devisCount    = $client->devis()->count();
        $facturesCount = $client->factures()->count();
        $pvsCount      = $client->pvs()->count();

        if ($devisCount > 0 || $facturesCount > 0 || $pvsCount > 0) {
            return redirect()
                ->route('admin.clients.index')
                ->with('error', "Impossible de supprimer ce client. Il possède {$devisCount} devis, {$facturesCount} facture(s) et {$pvsCount} PV.");
        }

        $nomComplet = $client->nom_complet;
        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Le client {$nomComplet} a été supprimé avec succès.");
    }

    /**
     * Activer/Désactiver un client
     */
    public function toggleStatus(Client $client)
    {
        $client->update(['actif' => !$client->actif]);

        $status = $client->actif ? 'activé' : 'désactivé';

        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Le client {$client->nom_complet} a été {$status} avec succès.");
    }
}