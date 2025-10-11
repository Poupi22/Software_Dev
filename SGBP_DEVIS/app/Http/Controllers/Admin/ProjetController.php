<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\ProjetPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjetController extends Controller
{
    public function index(Request $request)
    {
        $projets = Projet::with('photos')
            ->when($request->search, fn($q, $s) => $q->where('titre', 'like', "%{$s}%"))
            ->when($request->categorie, fn($q, $c) => $q->where('categorie', $c))
            ->orderBy('ordre')
            ->paginate(15)
            ->withQueryString();

        $categories = Projet::distinct()->pluck('categorie')->filter();

        return view('admin.projets.index', compact('projets', 'categories'));
    }

    public function create()
    {
        return view('admin.projets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'client_nom'  => 'nullable|string|max:255',
            'lieu'        => 'nullable|string|max:255',
            'categorie'   => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'duree'       => 'nullable|string|max:100',
            'superficie'  => 'nullable|string|max:100',
            'annee'       => 'nullable|integer|min:1900|max:2100',
            'ordre'       => 'nullable|integer|min:0',
            'actif'       => 'boolean',
            // Multi-photos (max 10, chaque photo max 3 Mo)
            'photos'      => 'nullable|array|max:10',
            'photos.*'    => 'image|max:3072',
            'photo_principale' => 'nullable|integer', // index de la photo principale
        ]);

        $validated['actif'] = $request->boolean('actif', true);
        unset($validated['photos'], $validated['photo_principale']);

        $projet = Projet::create($validated);

        // Sauvegarder les photos
        if ($request->hasFile('photos')) {
            $this->savePhotos($projet, $request->file('photos'), (int) $request->input('photo_principale', 0));
        }

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet créé avec succès !');
    }

    public function edit(Projet $projet)
    {
        $projet->load('photos');
        return view('admin.projets.edit', compact('projet'));
    }

    public function update(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'client_nom'  => 'nullable|string|max:255',
            'lieu'        => 'nullable|string|max:255',
            'categorie'   => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'duree'       => 'nullable|string|max:100',
            'superficie'  => 'nullable|string|max:100',
            'annee'       => 'nullable|integer|min:1900|max:2100',
            'ordre'       => 'nullable|integer|min:0',
            'actif'       => 'boolean',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|max:3072',
            'photo_principale' => 'nullable|integer',
            'supprimer_photos' => 'nullable|array',
            'supprimer_photos.*' => 'integer|exists:projet_photos,id',
        ]);

        $validated['actif'] = $request->boolean('actif', true);
        unset($validated['photos'], $validated['photo_principale'], $validated['supprimer_photos']);

        $projet->update($validated);

        // Supprimer les photos cochées
        if ($request->filled('supprimer_photos')) {
            foreach ($request->input('supprimer_photos') as $photoId) {
                $photo = ProjetPhoto::find($photoId);
                if ($photo && $photo->projet_id === $projet->id) {
                    Storage::disk('public')->delete($photo->path);
                    $photo->delete();
                }
            }
        }

        // Ajouter les nouvelles photos (dans la limite de 10 au total)
        if ($request->hasFile('photos')) {
            $existingCount = $projet->photos()->count();
            $maxNew        = max(0, 10 - $existingCount);
            $newPhotos     = array_slice($request->file('photos'), 0, $maxNew);
            if (!empty($newPhotos)) {
                $this->savePhotos($projet, $newPhotos, (int) $request->input('photo_principale', -1));
            }
        }

        // Mettre à jour la photo principale si demandé
        if ($request->filled('set_principale')) {
            $projet->photos()->update(['principale' => false]);
            ProjetPhoto::where('id', $request->input('set_principale'))
                ->where('projet_id', $projet->id)
                ->update(['principale' => true]);
        }

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet modifié avec succès !');
    }

    public function destroy(Projet $projet)
    {
        // Supprimer toutes les photos
        foreach ($projet->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        // Supprimer l'ancienne image principale si elle existe
        if ($projet->image_path) {
            Storage::disk('public')->delete($projet->image_path);
        }

        $projet->delete();

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet supprimé avec succès !');
    }

    /**
     * Supprime une photo via AJAX
     */
    public function deletePhoto(Request $request, Projet $projet, ProjetPhoto $photo)
    {
        if ($photo->projet_id !== $projet->id) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Définit une photo comme principale via AJAX
     */
    public function setPrincipale(Request $request, Projet $projet, ProjetPhoto $photo)
    {
        if ($photo->projet_id !== $projet->id) {
            abort(403);
        }

        $projet->photos()->update(['principale' => false]);
        $photo->update(['principale' => true]);

        return response()->json(['success' => true]);
    }

    // ── MÉTHODE PRIVÉE ────────────────────────────────────────────────────
    private function savePhotos(Projet $projet, array $files, int $principaleIndex = 0): void
    {
        // Réinitialiser la photo principale si on en ajoute de nouvelles
        if ($principaleIndex >= 0 && count($files) > 0) {
            $projet->photos()->update(['principale' => false]);
        }

        $ordre = $projet->photos()->max('ordre') ?? 0;

        foreach ($files as $index => $file) {
            $path = $file->store('projets', 'public');
            ProjetPhoto::create([
                'projet_id'  => $projet->id,
                'path'       => $path,
                'ordre'      => ++$ordre,
                'principale' => ($index === $principaleIndex),
            ]);
        }

        // Si aucune photo principale définie, mettre la première
        if (!$projet->photos()->where('principale', true)->exists()) {
            $first = $projet->photos()->orderBy('ordre')->first();
            if ($first) $first->update(['principale' => true]);
        }
    }
}
