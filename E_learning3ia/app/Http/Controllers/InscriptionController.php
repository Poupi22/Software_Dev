<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Programme;
use App\Models\ProgrammeSession;
use App\Models\User;
use App\Models\Paiement;
use App\Mail\UserCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;

class InscriptionController extends Controller
{
    public function index()
    {
        $inscriptions = Inscription::with('user', 'programmeSession.programme.formation','programmeSession.programme.qualification', 'paiements')->latest()->paginate(10);
        return view('admin_site.inscriptions.index', compact('inscriptions'));
    }

    public function create()
    {
        $programmes = Programme::with(['formation', 'qualification', 'sessions.anneeAcademique'])
            ->whereHas('sessions', function ($query) {
                $query->whereIn('statut', ['Programmée', 'Ouverte aux inscriptions']);
            })->get();
        return view('admin_site.inscriptions.create', compact('programmes'));
    }


    public function store(Request $request)
    {
        // --- VALIDATION ADAPTATIVE ---
        $validated = $request->validate([
            'inscription_type' => 'required|in:new,existing',

            // Champs communs
            'programme_session_id' => 'required|exists:programme_sessions,id',
            'verse' => 'required|integer|min:0',

            // Ajout de 'nullable' ici
            'user_id' => 'required_if:inscription_type,existing|nullable|exists:users,id',

            // Ajout de 'nullable' à tous les champs conditionnels
            'name' => 'required_if:inscription_type,new|nullable|string|max:255',
            'prenom' => 'required_if:inscription_type,new|nullable|string|max:255',
            'email' => 'required_if:inscription_type,new|nullable|email|max:255|unique:users,email',
            'date_naissance' => 'required_if:inscription_type,new|nullable|date',
            'lieu_naissance' => 'required_if:inscription_type,new|nullable|string|max:255',
            'sexe' => 'required_if:inscription_type,new|nullable|string',
            'nationalite' => 'required_if:inscription_type,new|nullable|string|max:255',
            'tel1' => 'required_if:inscription_type,new|nullable|string|max:20',
            'tel2' => 'nullable|string|max:20', // Déjà nullable, c'est bon
            'ville' => 'required_if:inscription_type,new|nullable|string|max:255',
            'tuteur' => 'required_if:inscription_type,new|nullable|string|max:255',
            'tel_tuteur' => 'required_if:inscription_type,new|nullable|string|max:20',

            // Les fichiers sont déjà 'nullable' par défaut, c'est bon
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cni' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'demande' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $session = ProgrammeSession::with('programme')->findOrFail($validated['programme_session_id']);
            $programme = $session->programme;

            if ($validated['verse'] > $programme->prix) {
                return back()->withInput()->with('error', 'Le montant versé ne peut excéder le coût total du programme.');
            }

            // --- BIFURCATION DE LA LOGIQUE ---
            if ($validated['inscription_type'] === 'new') {
                // == CAS 1 : NOUVEL ÉTUDIANT (VOTRE LOGIQUE ORIGINELLE EST ICI) ==
                $password = Str::random(10);
                $userData = $request->only('name', 'prenom', 'date_naissance', 'lieu_naissance', 'sexe', 'nationalite', 'tel1', 'tel2', 'email', 'ville', 'tuteur', 'tel_tuteur');
                $userData['password'] = Hash::make($password);
                $userData['matricule'] = 'ETD-' . now()->format('Y') . '-' . random_int(1000, 9999);

                if ($request->hasFile('photo')) $userData['photo'] = $request->file('photo')->store('photos_etudiants', 'public');
                if ($request->hasFile('cni')) $userData['cni'] = $request->file('cni')->store('cni_etudiants', 'public');
                if ($request->hasFile('demande')) $userData['demande'] = $request->file('demande')->store('demandes_etudiants', 'public');

                $user = User::create($userData);
                $user->assignRole('Etudiant');
                Mail::to($user->email)->send(new UserCredentialsMail($user->name, $user->email, $password));

            } else { // 'existing'
                // == CAS 2 : ÉTUDIANT EXISTANT ==
                $user = User::findOrFail($validated['user_id']);

                // Sécurité : vérifier que l'étudiant n'est pas déjà inscrit à cette session
                if ($user->inscriptions()->where('programme_session_id', $session->id)->exists()) {
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Cet étudiant est déjà inscrit à cette session de programme.');
                }
            }

            // --- LOGIQUE COMMUNE : CRÉATION DE L'INSCRIPTION ET DU PAIEMENT ---
            $inscription = Inscription::create([
                'user_id' => $user->id,
                'programme_session_id' => $session->id,
                'verse' => $validated['verse'],
                'reste' => $programme->prix - $validated['verse'],
            ]);

            if ($validated['verse'] > 0) {
                Paiement::create(['inscription_id' => $inscription->id, 'montant' => $validated['verse']]);
            }

            DB::commit();
            $message = $validated['inscription_type'] === 'new'
                ? 'Inscription effectuée. Les identifiants ont été envoyés.'
                : 'Inscription de l\'étudiant existant effectuée avec succès.';

            return redirect()->route('dashboard.inscription.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show(Inscription $inscription)
    {
        $inscription->load(['user', 'programmeSession.programme.formation', 'programmeSession.programme.qualification', 'programmeSession.anneeAcademique', 'paiements']);
        return view('admin_site.inscriptions.show', compact('inscription'));
    }

    public function edit(Inscription $inscription)
    {
        $inscription->load('programmeSession.programme');
        $programmes = Programme::with(['formation', 'qualification', 'sessions.anneeAcademique'])
            ->whereHas('sessions', function ($query) {
                $query->whereIn('statut', ['Programmée', 'Ouverte aux inscriptions']);
            })->get();
        return view('admin_site.inscriptions.edit', compact('inscription', 'programmes'));
    }

    public function update(Request $request, Inscription $inscription)
    {
        $validated = $request->validate([
            'programme_session_id' => 'required|exists:programme_sessions,id',
            'verse' => 'required|integer|min:0',
        ]);

        $session = ProgrammeSession::with('programme')->findOrFail($validated['programme_session_id']);
        $total = $session->programme->prix;

        if ($validated['verse'] > $total) {
            return back()->withInput()->with('error', 'Le montant total versé ne peut pas dépasser le coût du programme.');
        }

        $inscription->update([
            'programme_session_id' => $validated['programme_session_id'],
            'verse' => $validated['verse'],
            'reste' => $total - $validated['verse'],
        ]);

        return redirect()->route('dashboard.inscription.index')->with('success', 'Inscription mise à jour avec succès.');
    }

    public function destroy(Inscription $inscription)
    {
        $inscription->delete();
        return redirect()->route('dashboard.inscription.index')->with('success', 'Inscription supprimée. L\'étudiant est conservé.');
    }

    public function addPaiement(Request $request, Inscription $inscription)
    {
        $validated = $request->validate(['montant' => 'required|integer|min:1|lte:' . $inscription->reste]);
        DB::beginTransaction();
        try {
            Paiement::create(['inscription_id' => $inscription->id, 'montant' => $validated['montant']]);
            $inscription->increment('verse', $validated['montant']);
            $inscription->decrement('reste', $validated['montant']);
            DB::commit();
            return back()->with('success', 'Paiement enregistré.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du paiement.');
        }
    }

    public function genererSituationFinanciere(Inscription $inscription)
    {
        $inscription->load(['user', 'programmeSession.programme.formation', 'programmeSession.programme.qualification', 'paiements']);
        $dat = now()->format('d/m/Y');
        $pdf = PDF::loadView('admin_site.inscriptions.recu', compact('inscription', 'dat'))->setPaper('a4', 'portrait');
        return $pdf->stream('situation_financiere_' . $inscription->user->matricule . '.pdf');
    }
}
