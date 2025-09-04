<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::latest()->paginate(10);
        return view('admin_site.formations.index', compact('formations'));
    }

    public function create()
    {
        return view('admin_site.formations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:formations,nom',
            'code' => 'required|string|max:255|unique:formations,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('formations', 'public');
            $validated['image'] = $path;
        }

        Formation::create($validated);

        return redirect()->route('dashboard.formation.index')->with('success', 'Formation créée avec succès.');
    }

    public function show(Formation $formation)
    {
        return view('admin_site.formations.show', compact('formation'));
    }

    public function edit(Formation $formation)
    {
        return view('admin_site.formations.edit', compact('formation'));
    }
    public function update(Request $request, Formation $formation)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:formations,nom,' . $formation->id,
            'code' => 'required|string|max:255|unique:formations,code,' . $formation->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        if ($request->hasFile('image')) {
            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }
            $path = $request->file('image')->store('formations', 'public');
            $validated['image'] = $path;
        }

        $formation->update($validated);

        return redirect()->route('dashboard.formation.index')->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroy(Formation $formation)
    {
        if ($formation->programmes()->exists()) {
            return redirect()->route('dashboard.formation.index')
            ->with('error', 'Impossible de supprimer cette formation car elle est utilisée par un ou plusieurs programmes.');
        }

        try {
            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }

            $formation->delete();
            return redirect()->route('dashboard.formation.index')->with('success', 'Formation supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.formation.index')->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
}
