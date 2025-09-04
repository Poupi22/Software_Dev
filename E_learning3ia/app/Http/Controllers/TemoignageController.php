<?php

namespace App\Http\Controllers;

use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TemoignageController extends Controller
{
    /**
     * Affiche la liste des témoignages
     */
    public function index()
    {
        $temoignages = Temoignage::latest()->paginate(10);
        return view('admin_site.temoignages.index', compact('temoignages'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $defaults = [
            'note' => 5,
            'publie' => true
        ];

        $options = [
            'professions' => [
                'Etudiant' => 'Etudiant',
                'Partenaire' => 'Partenaire',
                'Employé' => 'Employé',
                'Autre' => 'Autre'
            ],
            'notes' => [
                1 => '1 étoile',
                2 => '2 étoiles',
                3 => '3 étoiles',
                4 => '4 étoiles',
                5 => '5 étoiles'
            ]
        ];

        $uploadConfig = [
            'max_size' => 2048,
            'mimes' => 'jpeg,png,jpg,gif',
            'dimensions' => 'Ratio 1:1 recommandé'
        ];

        return view('admin_site.temoignages.create', compact('defaults', 'options', 'uploadConfig'));
    }

    /**
     * Enregistre un nouveau témoignage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'message' => 'required|string',
            'note' => 'nullable|integer|between:1,5',
            'publie' => 'sometimes|accepted'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['publie'] = $request->has('publie');

        try {
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('temoignages', 'public');
                if (!$path) {
                    throw new \Exception("Échec de l'upload de la photo");
                }
                $data['photo'] = $path;
            }

            $temoignage = Temoignage::create($data);

            if (!$temoignage) {
                throw new \Exception("Échec de la création en base de données");
            }

            return redirect()
                ->route('dashboard.temoignage.index')
                ->with('success', 'Témoignage créé avec succès!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', "Erreur: " . $e->getMessage());
        }
    }

    /**
     * Affiche un témoignage spécifique
     */
    public function show(Temoignage $temoignage)
    {
        return view('admin_site.temoignages.show', compact('temoignage'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    /**
 * Affiche le formulaire d'édition
 */
public function edit(Temoignage $temoignage)
{
    $options = [
        'professions' => [
            'Etudiant' => 'Etudiant',
            'Partenaire' => 'Partenaire',
            'Employé' => 'Employé',
            'Autre' => 'Autre'
        ],
        'notes' => [  // Add notes options
            1 => '1 étoile',
            2 => '2 étoiles',
            3 => '3 étoiles',
            4 => '4 étoiles',
            5 => '5 étoiles'
        ]
    ];

    $defaults = [  // Add defaults
        'note' => 5,
        'publie' => true
    ];

    $uploadConfig = [  // Add uploadConfig
        'max_size' => 2048,
        'mimes' => 'jpeg,png,jpg,gif',
        'dimensions' => 'Ratio 1:1 recommandé'
    ];

    return view('admin_site.temoignages.edit', compact(
        'temoignage',
        'options',
        'defaults',
        'uploadConfig'
    ));
}

    /**
     * Met à jour un témoignage
     */
    public function update(Request $request, Temoignage $temoignage)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'message' => 'required|string',
            'note' => 'nullable|integer|between:1,5',
            'publie' => 'boolean'
        ]);

        // Gestion du statut publié
        $validated['publie'] = $request->has('publie');

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($temoignage->photo) {
                Storage::disk('public')->delete($temoignage->photo);
            }
            $validated['photo'] = $request->file('photo')->store('temoignages', 'public');
        }

        // Gestion de la suppression de photo
        if ($request->has('remove_photo') && $temoignage->photo) {
            Storage::disk('public')->delete($temoignage->photo);
            $validated['photo'] = null;
        }

        $temoignage->update($validated);

        return redirect()
            ->route('dashboard.temoignage.index')
            ->with('success', 'Témoignage mis à jour avec succès');
    }

    /**
     * Supprime un témoignage
     */
    public function destroy(Temoignage $temoignage)
    {
        try {
            if ($temoignage->photo) {
                Storage::disk('public')->delete($temoignage->photo);
            }

            $temoignage->delete();

            return redirect()
                ->route('dashboard.temoignage.index')
                ->with('success', 'Témoignage supprimé avec succès');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

}
