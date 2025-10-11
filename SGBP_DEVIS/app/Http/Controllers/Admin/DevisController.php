<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureCategory;
use App\Models\FactureArticle;
use App\Models\Client;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $query = Devis::with('client', 'createdBy');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero', 'like', "%{$request->search}%")
                ->orWhereHas('client', fn($q) => $q->where('nom', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('devise')) $query->where('devise', $request->devise);

        $devis = $query->latest()->paginate(15);

        return view('admin.devis.index', compact('devis'));
    }

    public function create()
    {
        $clients              = Client::actifs()->get();
        $articles             = Article::actifs()->get();
        $categoriesExistantes = Category::actifs()->ordered()->get();

        return view('admin.devis.create', compact('clients', 'articles', 'categoriesExistantes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'          => 'required|string|max:255',
            'client_id'      => 'required|exists:clients,id',
            'devise'         => 'required|in:FCFA,EUR,USD',
            'validite_mois'  => 'required|integer|in:1,2,3,6,12',
            'introduction'   => 'nullable|string',
            'conclusion'     => 'nullable|string',
            'main_oeuvre'               => 'nullable|numeric|min:0',
            'main_oeuvre_pourcentage'   => 'nullable|numeric|min:0|max:100',

            'categories'                                          => 'nullable|array',
            'categories.*.nom'                                    => 'required_with:categories|string',
            'categories.*.main_oeuvre'                            => 'nullable|numeric|min:0',
            'categories.*.main_oeuvre_pourcentage'                => 'nullable|numeric|min:0|max:100',
            'categories.*.articles'                               => 'nullable|array',
            'categories.*.articles.*.article_id'                  => 'required_with:categories.*.articles|string',
            'categories.*.articles.*.nouveau_nom'                 => 'nullable|required_if:categories.*.articles.*.article_id,new|string|max:255',
            'categories.*.articles.*.unite'                       => 'nullable|string|max:50',
            'categories.*.articles.*.quantite'                    => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.prix_unitaire_ht'            => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.remise_pourcentage'          => 'nullable|numeric|min:0|max:100',

            'articles_sans_categorie'                             => 'nullable|array',
            'articles_sans_categorie.*.article_id'                => 'required_with:articles_sans_categorie|string',
            'articles_sans_categorie.*.nouveau_nom'               => 'nullable|required_if:articles_sans_categorie.*.article_id,new|string|max:255',
            'articles_sans_categorie.*.unite'                     => 'nullable|string|max:50',
            'articles_sans_categorie.*.quantite'                  => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.prix_unitaire_ht'          => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.remise_pourcentage'        => 'nullable|numeric|min:0|max:100',
        ]);

        $hasArticles = !empty($validated['categories']) || !empty($validated['articles_sans_categorie']);
        if (!$hasArticles) {
            return back()->withErrors(['articles' => 'Veuillez ajouter au moins un article au devis.'])->withInput();
        }

        $validated['created_by']  = auth()->id();
        $validated['statut']      = 'brouillon';
        $validated['type']        = 'provisoire';
        $validated['main_oeuvre'] = $validated['main_oeuvre'] ?? 0;
        $validated['main_oeuvre_pourcentage'] = $validated['main_oeuvre_pourcentage'] ?? null;

        $maxAttempts = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return DB::transaction(function () use ($validated) {

                    $devis = Devis::create($validated);

                    // ── Catégories + articles ──
                    if (!empty($validated['categories'])) {
                        foreach ($validated['categories'] as $ordre => $catData) {

                            $categoryModel = Category::firstOrCreate(
                                ['nom' => $catData['nom']],
                                [
                                    'slug'    => Str::slug($catData['nom']),
                                    'actif'   => true,
                                    'ordre'   => Category::max('ordre') + 1,
                                    'icone'   => 'category',
                                    'couleur' => '#2563EB',
                                ]
                            );

                            $devisCategory = $devis->categories()->create([
                                'nom'                    => $catData['nom'],
                                'ordre'                  => $ordre,
                                'main_oeuvre'            => $catData['main_oeuvre'] ?? 0,
                                'main_oeuvre_pourcentage' => $catData['main_oeuvre_pourcentage'] ?? null,
                            ]);

                            if (!empty($catData['articles'])) {
                                foreach ($catData['articles'] as $artOrdre => $artData) {

                                    if ($artData['article_id'] === 'new') {
                                        $newArticle = Article::create([
                                            'type'            => 'produit',
                                            'nom'             => $artData['nouveau_nom'],
                                            'unite'           => $artData['unite'],
                                            'prix_ht'         => $artData['prix_unitaire_ht'],
                                            'prix_modifiable' => true,
                                            'actif'           => true,
                                            'created_by'      => auth()->id(),
                                        ]);
                                        $newArticle->categories()->attach($categoryModel->id);
                                        $articleId   = $newArticle->id;
                                        $designation = $newArticle->nom;
                                    } else {
                                        $article     = Article::findOrFail($artData['article_id']);
                                        $articleId   = $article->id;
                                        $designation = $article->nom;
                                    }

                                    $devis->articles()->create([
                                        'devis_category_id'  => $devisCategory->id,
                                        'article_id'         => $articleId,
                                        'designation'        => $designation,
                                        'unite'              => $artData['unite'],
                                        'quantite'           => $artData['quantite'],
                                        'prix_unitaire_ht'   => $artData['prix_unitaire_ht'],
                                        'remise_pourcentage' => $artData['remise_pourcentage'] ?? 0,
                                        'ordre'              => $artOrdre,
                                    ]);
                                }
                            }
                        }
                    }

                    // ── Articles sans catégorie ──
                    if (!empty($validated['articles_sans_categorie'])) {
                        foreach ($validated['articles_sans_categorie'] as $ordre => $artData) {

                            if ($artData['article_id'] === 'new') {
                                $newArticle = Article::create([
                                    'type'            => 'produit',
                                    'nom'             => $artData['nouveau_nom'],
                                    'unite'           => $artData['unite'],
                                    'prix_ht'         => $artData['prix_unitaire_ht'],
                                    'prix_modifiable' => true,
                                    'actif'           => true,
                                    'created_by'      => auth()->id(),
                                ]);
                                $articleId   = $newArticle->id;
                                $designation = $newArticle->nom;
                            } else {
                                $article     = Article::findOrFail($artData['article_id']);
                                $articleId   = $article->id;
                                $designation = $article->nom;
                            }

                            $devis->articles()->create([
                                'devis_category_id'  => null,
                                'article_id'         => $articleId,
                                'designation'        => $designation,
                                'unite'              => $artData['unite'],
                                'quantite'           => $artData['quantite'],
                                'prix_unitaire_ht'   => $artData['prix_unitaire_ht'],
                                'remise_pourcentage' => $artData['remise_pourcentage'] ?? 0,
                                'ordre'              => $ordre,
                            ]);
                        }
                    }

                    $devis->calculerTotaux();

                    return redirect()->route('admin.devis.show', $devis)
                        ->with('success', 'Devis créé avec succès ! Numéro : ' . $devis->numero);
                });

            } catch (\Illuminate\Database\QueryException $e) {
                $isDuplicate = $e->errorInfo[1] == 1062
                    || str_contains($e->getMessage(), 'UNIQUE constraint failed');

                if ($isDuplicate && str_contains($e->getMessage(), 'numero') && $attempt < $maxAttempts) {
                    continue;
                }

                if ($isDuplicate && str_contains($e->getMessage(), 'numero')) {
                    return back()->withErrors(['numero' => 'Erreur de numérotation, veuillez réessayer.'])->withInput();
                }

                \Log::error('Devis creation failed: ' . $e->getMessage());
                return back()->withErrors(['general' => 'Erreur lors de la création : ' . $e->errorInfo[2]])->withInput();
            }
        }
    }

    public function show(Devis $devis)
    {
        $devis->load('client', 'articles.article', 'categories', 'createdBy', 'updatedBy');
        return view('admin.devis.show', compact('devis'));
    }

    public function edit(Devis $devis)
    {
        $peutModifier = $devis->statut === 'brouillon'
            || ($devis->type === 'provisoire' && !in_array($devis->statut, ['accepte', 'refuse', 'expire']));

        if (!$peutModifier) {
            return back()->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $clients              = Client::actifs()->get();
        $articles             = Article::actifs()->get();
        $categoriesExistantes = Category::actifs()->ordered()->get();
        $devis->load('articles', 'categories');

        return view('admin.devis.edit', compact('devis', 'clients', 'articles', 'categoriesExistantes'));
    }

    public function update(Request $request, Devis $devis)
    {
        $peutModifier = $devis->statut === 'brouillon'
            || ($devis->type === 'provisoire' && !in_array($devis->statut, ['accepte', 'refuse', 'expire']));

        if (!$peutModifier) {
            return back()->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'titre'         => 'required|string|max:255',
            'client_id'     => 'required|exists:clients,id',
            'devise'        => 'required|in:FCFA,EUR,USD',
            'validite_mois' => 'required|integer|in:1,2,3,6,12',
            'introduction'  => 'nullable|string',
            'conclusion'    => 'nullable|string',
            'main_oeuvre'              => 'nullable|numeric|min:0',
            'main_oeuvre_pourcentage'  => 'nullable|numeric|min:0|max:100',

            'categories'                                          => 'nullable|array',
            'categories.*.nom'                                    => 'required_with:categories|string',
            'categories.*.main_oeuvre'                            => 'nullable|numeric|min:0',
            'categories.*.main_oeuvre_pourcentage'                => 'nullable|numeric|min:0|max:100',
            'categories.*.articles'                               => 'nullable|array',
            'categories.*.articles.*.article_id'                  => 'required_with:categories.*.articles|string',
            'categories.*.articles.*.nouveau_nom'                 => 'nullable|required_if:categories.*.articles.*.article_id,new|string|max:255',
            'categories.*.articles.*.unite'                       => 'nullable|string|max:50',
            'categories.*.articles.*.quantite'                    => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.prix_unitaire_ht'            => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.remise_pourcentage'          => 'nullable|numeric|min:0|max:100',

            'articles_sans_categorie'                             => 'nullable|array',
            'articles_sans_categorie.*.article_id'                => 'required_with:articles_sans_categorie|string',
            'articles_sans_categorie.*.nouveau_nom'               => 'nullable|required_if:articles_sans_categorie.*.article_id,new|string|max:255',
            'articles_sans_categorie.*.unite'                     => 'nullable|string|max:50',
            'articles_sans_categorie.*.quantite'                  => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.prix_unitaire_ht'          => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.remise_pourcentage'        => 'nullable|numeric|min:0|max:100',
        ]);

        $hasArticles = !empty($validated['categories']) || !empty($validated['articles_sans_categorie']);
        if (!$hasArticles) {
            return back()->withErrors(['articles' => 'Veuillez ajouter au moins un article au devis.'])->withInput();
        }

        $validated['main_oeuvre'] = $validated['main_oeuvre'] ?? 0;
        $validated['main_oeuvre_pourcentage'] = $validated['main_oeuvre_pourcentage'] ?? null;
        $validated['updated_by']  = auth()->id();

        return DB::transaction(function () use ($devis, $validated) {

            $devis->update($validated);

            $devis->categories()->delete();
            $devis->articles()->delete();

            if (!empty($validated['categories'])) {
                foreach ($validated['categories'] as $ordre => $catData) {

                    $categoryModel = Category::firstOrCreate(
                        ['nom' => $catData['nom']],
                        [
                            'slug'    => Str::slug($catData['nom']),
                            'actif'   => true,
                            'ordre'   => Category::max('ordre') + 1,
                            'icone'   => 'category',
                            'couleur' => '#2563EB',
                        ]
                    );

                    $devisCategory = $devis->categories()->create([
                        'nom'                    => $catData['nom'],
                        'ordre'                  => $ordre,
                        'main_oeuvre'            => $catData['main_oeuvre'] ?? 0,
                        'main_oeuvre_pourcentage' => $catData['main_oeuvre_pourcentage'] ?? null,
                    ]);

                    if (!empty($catData['articles'])) {
                        foreach ($catData['articles'] as $artOrdre => $artData) {

                            if ($artData['article_id'] === 'new') {
                                $newArticle = Article::create([
                                    'type'            => 'produit',
                                    'nom'             => $artData['nouveau_nom'],
                                    'unite'           => $artData['unite'],
                                    'prix_ht'         => $artData['prix_unitaire_ht'],
                                    'prix_modifiable' => true,
                                    'actif'           => true,
                                    'created_by'      => auth()->id(),
                                ]);
                                $newArticle->categories()->attach($categoryModel->id);
                                $articleId   = $newArticle->id;
                                $designation = $newArticle->nom;
                            } else {
                                $article     = Article::findOrFail($artData['article_id']);
                                $articleId   = $article->id;
                                $designation = $article->nom;
                            }

                            $devis->articles()->create([
                                'devis_category_id'  => $devisCategory->id,
                                'article_id'         => $articleId,
                                'designation'        => $designation,
                                'unite'              => $artData['unite'],
                                'quantite'           => $artData['quantite'],
                                'prix_unitaire_ht'   => $artData['prix_unitaire_ht'],
                                'remise_pourcentage' => $artData['remise_pourcentage'] ?? 0,
                                'ordre'              => $artOrdre,
                            ]);
                        }
                    }
                }
            }

            if (!empty($validated['articles_sans_categorie'])) {
                foreach ($validated['articles_sans_categorie'] as $ordre => $artData) {

                    if ($artData['article_id'] === 'new') {
                        $newArticle = Article::create([
                            'type'            => 'produit',
                            'nom'             => $artData['nouveau_nom'],
                            'unite'           => $artData['unite'],
                            'prix_ht'         => $artData['prix_unitaire_ht'],
                            'prix_modifiable' => true,
                            'actif'           => true,
                            'created_by'      => auth()->id(),
                        ]);
                        $articleId   = $newArticle->id;
                        $designation = $newArticle->nom;
                    } else {
                        $article     = Article::findOrFail($artData['article_id']);
                        $articleId   = $article->id;
                        $designation = $article->nom;
                    }

                    $devis->articles()->create([
                        'devis_category_id'  => null,
                        'article_id'         => $articleId,
                        'designation'        => $designation,
                        'unite'              => $artData['unite'],
                        'quantite'           => $artData['quantite'],
                        'prix_unitaire_ht'   => $artData['prix_unitaire_ht'],
                        'remise_pourcentage' => $artData['remise_pourcentage'] ?? 0,
                        'ordre'              => $ordre,
                    ]);
                }
            }

            $devis->calculerTotaux();

            return redirect()->route('admin.devis.show', $devis)
                ->with('success', 'Devis mis à jour avec succès !');
        });
    }

    public function destroy(Devis $devis)
    {
        if ($devis->statut !== 'brouillon') {
            return back()->with('error', 'Seuls les devis brouillons peuvent être supprimés.');
        }

        $devis->delete();

        return redirect()->route('admin.devis.index')
            ->with('success', 'Devis supprimé avec succès !');
    }

    public function send(Request $request, Devis $devis)
    {
        $request->validate([
            'email_destinataire'   => 'required|email',
            'message_personnalise' => 'nullable|string|max:2000',
        ]);

        $devis->load('client', 'articles.article', 'categories');

        Mail::to($request->email_destinataire)
            ->send(new \App\Mail\DevisMail($devis, $request->message_personnalise));

        if ($devis->statut === 'brouillon') {
            $devis->update(['statut' => 'envoye', 'date_envoi' => now()]);
        } else {
            $devis->update(['date_envoi' => now()]);
        }

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'devis_envoye',
            'titre'   => 'Devis envoyé',
            'message' => "Le devis {$devis->numero} ({$devis->type}) a été envoyé à {$request->email_destinataire}.",
            'lien'    => route('admin.devis.show', $devis),
            'icone'   => 'send',
            'couleur' => 'blue',
        ]);

        return back()->with('success', "Devis envoyé par email à {$request->email_destinataire} avec succès !");
    }

   public function finaliser(Request $request, Devis $devis)
{
    if ($devis->type === 'final') {
        return back()->with('error', 'Ce devis est déjà finalisé.');
    }

    $request->validate([
        'numero_bc'  => 'nullable|string|max:100',
        'fichier_bc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    DB::transaction(function () use ($request, $devis) {
        // Sauvegarder le bon de commande si au moins une donnée est fournie
        if ($request->filled('numero_bc') || $request->hasFile('fichier_bc')) {
            $data = ['devis_id' => $devis->id];

            if ($request->filled('numero_bc')) {
                $data['numero'] = $request->numero_bc;
            }

            if ($request->hasFile('fichier_bc')) {
                $file = $request->file('fichier_bc');
                $path = $file->store('bons_commande', 'local');
                $data['fichier_path'] = $path;
                $data['fichier_nom']  = $file->getClientOriginalName();
            }

            \App\Models\BonCommande::updateOrCreate(['devis_id' => $devis->id], $data);
        }

        // Finaliser le devis
        $devis->update(['type' => 'final']);

        // Notification
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'devis_finalise',
            'titre'   => 'Devis finalisé',
            'message' => "Le devis {$devis->numero} a été finalisé (version définitive).",
            'lien'    => route('admin.devis.show', $devis),
            'icone'   => 'check_circle',
            'couleur' => 'green',
        ]);
    });

    return back()->with('success', 'Devis finalisé ! Il ne peut plus être modifié.');
}

    public function accept(Devis $devis)
    {
        if ($devis->type !== 'final') {
            return back()->with('error', 'Seuls les devis finalisés peuvent être acceptés. Finalisez d\'abord le devis.');
        }

        if ($devis->statut === 'accepte') {
            return back()->with('error', 'Ce devis est déjà accepté.');
        }

        $devis->update(['statut' => 'accepte', 'date_acceptation' => now()]);

        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'devis_accepte',
            'titre'   => 'Devis accepté',
            'message' => "Le devis {$devis->numero} a été marqué comme accepté.",
            'lien'    => route('admin.devis.show', $devis),
            'icone'   => 'thumb_up',
            'couleur' => 'green',
        ]);

        return back()->with('success', 'Devis accepté ! Vous pouvez maintenant le convertir en facture.');
    }

    public function convertToFacture(Devis $devis)
    {
        if ($devis->statut !== 'accepte') {
            return back()->with('error', 'Seuls les devis acceptés peuvent être convertis.');
        }

        if ($devis->facture_id) {
            return back()->with('error', 'Ce devis a déjà été converti en facture.');
        }

        $devis->load('categories.articles', 'articles');

        $facture = Facture::create([
            'titre'           => $devis->titre,
            'client_id'       => $devis->client_id,
            'devis_id'        => $devis->id,
            'type'            => $devis->type,
            'devise'          => $devis->devise,
            'date_emission'   => now(),
            'date_echeance'   => now()->addDays(30),
            'introduction'    => $devis->introduction,
            'conclusion'      => $devis->conclusion,
            'main_oeuvre'     => $devis->main_oeuvre,
            'total_ht'        => $devis->total_ht,
            'taux_tps'        => $devis->taux_tps,
            'total_tps'       => $devis->total_tps,
            'taux_css'        => $devis->taux_css,
            'total_css'       => $devis->total_css,
            'total_ttc'       => $devis->total_ttc,
            'statut'          => 'brouillon',
            'statut_paiement' => 'non_paye',
            'montant_paye'    => 0,
            'created_by'      => auth()->id(),
        ]);

        foreach ($devis->categories as $devisCategory) {
            $factureCategory = $facture->categories()->create([
                'nom'         => $devisCategory->nom,
                'ordre'       => $devisCategory->ordre,
                'main_oeuvre' => $devisCategory->main_oeuvre,
            ]);

            foreach ($devisCategory->articles as $devisArticle) {
                $facture->articles()->create([
                    'facture_category_id' => $factureCategory->id,
                    'article_id'          => $devisArticle->article_id,
                    'designation'         => $devisArticle->designation,
                    'unite'               => $devisArticle->unite,
                    'quantite'            => $devisArticle->quantite,
                    'prix_unitaire_ht'    => $devisArticle->prix_unitaire_ht,
                    'remise_pourcentage'  => $devisArticle->remise_pourcentage,
                    'ordre'               => $devisArticle->ordre,
                ]);
            }
        }

        foreach ($devis->articles()->whereNull('devis_category_id')->get() as $devisArticle) {
            $facture->articles()->create([
                'facture_category_id' => null,
                'article_id'          => $devisArticle->article_id,
                'designation'         => $devisArticle->designation,
                'unite'               => $devisArticle->unite,
                'quantite'            => $devisArticle->quantite,
                'prix_unitaire_ht'    => $devisArticle->prix_unitaire_ht,
                'remise_pourcentage'  => $devisArticle->remise_pourcentage,
                'ordre'               => $devisArticle->ordre,
            ]);
        }

        $devis->update(['facture_id' => $facture->id]);

        return redirect()->route('admin.factures.show', $facture)
            ->with('success', 'Devis converti en facture avec succès ! Numéro : ' . $facture->numero);
    }

    public function generatePDF(Devis $devis)
    {
        $devis->load('client', 'articles.article', 'categories');
        $parametre = \App\Models\Parametre::first();

        $pdf = Pdf::loadView('admin.devis.pdf', compact('devis', 'parametre'));

        return $pdf->download('Devis-' . $devis->numero . '.pdf');
    }

    public function generatePDFSansCachet(Devis $devis)
    {
        $devis->load('client', 'articles.article', 'categories');
        $parametre = \App\Models\Parametre::first();

        $pdf = Pdf::loadView('admin.devis.facture-pdf', compact('devis', 'parametre'));

        return $pdf->download('Devis-' . $devis->numero . '-sans-cachet.pdf');
    }

    public function downloadBc(Devis $devis)
{
    abort_unless($devis->bonCommande?->fichier_path, 404);
    return Storage::disk('local')->download(
        $devis->bonCommande->fichier_path,
        $devis->bonCommande->fichier_nom
    );
}
}