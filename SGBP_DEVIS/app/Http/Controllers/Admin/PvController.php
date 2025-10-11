<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PvMail;
use App\Models\Pv;
use App\Models\Facture;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PvController extends Controller
{
    public function index(Request $request)
    {
        $query = Pv::with('facture.client', 'facture.devis.bonCommande', 'client', 'createdBy');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%")
                  ->orWhereHas('client', fn($c) => $c->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('entreprise', 'like', "%{$search}%"));
            });
        }

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        if ($etat = $request->input('etat_travaux')) {
            $query->where('etat_travaux', $etat);
        }

        $pvs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pvs.index', compact('pvs'));
    }

    public function create()
    {
        $factures = Facture::where('type', 'final')
            ->where('statut_paiement', 'paye')
            ->whereNull('pv_id')
            ->with('client', 'devis')
            ->get();

        $selectedFactureId = request('facture_id');

        return view('admin.pvs.create', compact('factures', 'selectedFactureId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'facture_id' => 'required|exists:factures,id',
            'date_reception' => 'required|date',
            'lieu_reception' => 'nullable|string|max:255',
            'description_travaux' => 'nullable|string',
            'observations' => 'nullable|string',
            'reserves' => 'nullable|string',
            'etat_travaux' => 'required|in:conforme,reserve_mineure,reserve_majeure,non_conforme',
        ]);

        $facture = Facture::findOrFail($validated['facture_id']);
        $validated['client_id'] = $facture->client_id;
        $validated['created_by'] = auth()->id();
        $validated['statut'] = 'brouillon';

        $pv = Pv::create($validated);

        // Lien bidirectionnel
        $facture->update(['pv_id' => $pv->id]);

        return redirect()->route('admin.pvs.show', $pv)
            ->with('success', 'PV créé avec succès ! Numéro : ' . $pv->numero);
    }

    public function show(Pv $pv)
    {
        $pv->load('facture.client', 'facture.devis.bonCommande', 'client', 'createdBy', 'updatedBy');
        return view('admin.pvs.show', compact('pv'));
    }

    public function edit(Pv $pv)
    {
        if ($pv->statut !== 'brouillon') {
            return back()->with('error', 'Seuls les PV brouillons peuvent être modifiés.');
        }

        $pv->load('facture.client', 'facture.devis.bonCommande');
        return view('admin.pvs.edit', compact('pv'));
    }

    public function update(Request $request, Pv $pv)
    {
        if ($pv->statut !== 'brouillon') {
            return back()->with('error', 'Seuls les PV brouillons peuvent être modifiés.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'date_reception' => 'required|date',
            'lieu_reception' => 'nullable|string|max:255',
            'description_travaux' => 'nullable|string',
            'observations' => 'nullable|string',
            'reserves' => 'nullable|string',
            'etat_travaux' => 'required|in:conforme,reserve_mineure,reserve_majeure,non_conforme',
        ]);

        $validated['updated_by'] = auth()->id();

        $pv->update($validated);

        return redirect()->route('admin.pvs.show', $pv)
            ->with('success', 'PV mis à jour avec succès !');
    }

    public function signer(Pv $pv)
    {
        if ($pv->statut !== 'brouillon') {
            return back()->with('error', 'Ce PV est déjà signé.');
        }

        $pv->update([
            'statut' => 'signe',
            'date_signature_entreprise' => now(),
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'PV signé avec succès !');
    }

    public function destroy(Pv $pv)
    {
        if ($pv->statut !== 'brouillon') {
            return back()->with('error', 'Seuls les PV brouillons peuvent être supprimés.');
        }

        // Retirer le lien sur la facture
        if ($pv->facture) {
            $pv->facture->update(['pv_id' => null]);
        }

        $pv->delete();

        return redirect()->route('admin.pvs.index')
            ->with('success', 'PV supprimé avec succès !');
    }

    public function send(Request $request, Pv $pv)
    {
        $request->validate([
            'email_destinataire' => 'required|email',
            'message_personnalise' => 'nullable|string|max:2000',
        ]);

        $pv->load('facture.client', 'facture.devis.bonCommande', 'client');

        Mail::to($request->email_destinataire)
            ->send(new PvMail($pv, $request->message_personnalise));

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'pv_envoye',
            'titre'   => 'PV envoyé',
            'message' => "Le PV {$pv->numero} a été envoyé à {$request->email_destinataire}.",
            'lien'    => route('admin.pvs.show', $pv),
            'icone'   => 'verified',
            'couleur' => 'purple',
        ]);

        return back()->with('success', 'PV envoyé par email avec succès !');
    }

    public function generatePDF(Pv $pv)
    {
        $pv->load('facture.client', 'facture.devis.bonCommande', 'client');
        $parametre = Parametre::get();

        $pdf = Pdf::loadView('admin.pvs.pdf', compact('pv', 'parametre'));

        return $pdf->download('PV-' . $pv->numero . '.pdf');
    }
}
