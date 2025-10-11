<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->ordered()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
            'couleur' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ]);

        $validated['actif'] = true;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string',
            'couleur' => 'nullable|string',
            'ordre' => 'nullable|integer',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    public function destroy(Category $category)
    {
        if ($category->articles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie contenant des articles.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès !');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['actif' => !$category->actif]);
        
        $status = $category->actif ? 'activée' : 'désactivée';
        return redirect()->route('admin.categories.index')
            ->with('success', "La catégorie {$category->nom} a été {$status}.");
    }
}
