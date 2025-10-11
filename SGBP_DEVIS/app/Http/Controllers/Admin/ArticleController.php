<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('categories', 'createdBy');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                ->orWhere('reference', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'produit') $query->produits();
            if ($request->type === 'service') $query->services();
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn($q) => $q->where('categories.id', $request->category));
        }

        if ($request->filled('status')) {
            if ($request->status === 'actif') $query->actifs();
            if ($request->status === 'inactif') $query->where('actif', false);
        }

        $allowedSorts = ['nom', 'reference', 'prix_ht', 'type', 'created_at'];
        $sortField = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $sortDir = $request->get('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $articles = $query->paginate(15);
        $categories = Category::actifs()->ordered()->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::actifs()->ordered()->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:produit,service',
            'nom' => 'required|string|max:255',
            'reference' => 'nullable|string|unique:articles,reference',
            'description' => 'nullable|string',
            'unite' => 'required|string',
            'prix_ht' => 'required|numeric|min:0',
            'prix_modifiable' => 'boolean',
            'gestion_stock' => 'boolean',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['prix_modifiable'] = $request->has('prix_modifiable');
        $validated['gestion_stock'] = $request->has('gestion_stock');
        $validated['actif'] = true;

        $article = Article::create($validated);
        if (!empty($validated['categories'])) {
            $article->categories()->attach($validated['categories']); // ou ->sync() pour update
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article créé avec succès !');
    }

    public function show(Article $article)
    {
        $article->load('categories', 'createdBy', 'updatedBy');
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $categories = Category::actifs()->ordered()->get();
        $articleCategories = $article->categories->pluck('id')->toArray();
        return view('admin.articles.edit', compact('article', 'categories', 'articleCategories'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'type' => 'required|in:produit,service',
            'nom' => 'required|string|max:255',
            'reference' => 'nullable|string|unique:articles,reference,' . $article->id,
            'description' => 'nullable|string',
            'unite' => 'required|string',
            'prix_ht' => 'required|numeric|min:0',
            'prix_modifiable' => 'boolean',
            'actif' => 'boolean',
            'gestion_stock' => 'boolean',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $validated['prix_modifiable'] = $request->has('prix_modifiable');
        $validated['actif'] = $request->has('actif');
        $validated['gestion_stock'] = $request->has('gestion_stock');
        $validated['updated_by'] = auth()->id();

        $article->update($validated);

        if (isset($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        } else {
            $article->categories()->sync([]);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès !');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès !');
    }

    public function toggleStatus(Article $article)
    {
        $article->update(['actif' => !$article->actif]);

        $status = $article->actif ? 'activé' : 'désactivé';
        return redirect()->route('admin.articles.index')
            ->with('success', "L'article {$article->nom} a été {$status}.");
    }
}
