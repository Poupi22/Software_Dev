<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Article;
use App\Models\Category;
use App\Mail\FactureMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with('client', 'devis', 'createdBy');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('numero', 'like', "%{$request->search}%")
                  ->orWhere('titre', 'like', "%{$request->search}%")
                  ->orWhereHas('client', fn($q) => $q->where('nom', 'like', "%{$request->search}%")
                      ->orWhere('raison_sociale', 'like', "%{$request->search}%"));
            });
        }

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('statut_paiement')) $query->where('statut_paiement', $request->statut_paiement);
        if ($request->filled('type')) $query->where('type', $request->type);

        $factures = $query->latest()->paginate(15);

        return view('admin.factures.index', compact('factures'));
    }

    public function create()
    {
        $clients = Client::actifs()->get();
        $devis = Devis::where('statut', 'accepte')->whereNull('facture_id')->get();
        $articles = Article::actifs()->get();
        $categoriesExistantes = Category::actifs()->ordered()->get();
        return view('admin.factures.create', compact('clients', 'devis', 'articles', 'categoriesExistantes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'               => 'required|string|max:255',
            'client_id'           => 'required|exists:clients,id',
            'devis_id'            => 'nullable|exists:devis,id',
            'type'                => 'required|in:provisoire,final',
            'devise'              => 'required|in:FCFA,EUR,USD',
            'date_emission'       => 'required|date',
            'date_echeance'       => 'required|date|after:date_emission',
            'introduction'        => 'nullable|string',
            'conclusion'          => 'nullable|string',
            'conditions_paiement' => 'nullable|string',
            'main_oeuvre'         => 'nullable|numeric|min:0',

            'categories'           => 'nullable|array',
            'categories.*.nom'     => 'required_with:categories|string',
            'categories.*.main_oeuvre' => 'nullable|numeric|min:0',
            'categories.*.articles'    => 'nullable|array',
            'categories.*.articles.*.article_id'       => 'required_with:categories.*.articles|string',
            'categories.*.articles.*.nouveau_nom'      => 'nullable|required_if:categories.*.articles.*.article_id,new|string|max:255',
            'categories.*.articles.*.unite'            => 'nullable|string|max:50',
            'categories.*.articles.*.quantite'         => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.prix_unitaire_ht' => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',

            'articles_sans_categorie' => 'nullable|array',
            'articles_sans_categorie.*.article_id'       => 'required_with:articles_sans_categorie|string',
            'articles_sans_categorie.*.nouveau_nom'      => 'nullable|required_if:articles_sans_categorie.*.article_id,new|string|max:255',
            'articles_sans_categorie.*.unite'            => 'nullable|string|max:50',
            'articles_sans_categorie.*.quantite'         => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.prix_unitaire_ht' => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $hasArticles = !empty($validated['categories']) || !empty($validated['articles_sans_categorie']);
        if (!$hasArticles) {
            return back()->withErrors(['articles' => 'Veuillez ajouter au moins un article à la facture.'])->withInput();
        }

        $validated['created_by']      = auth()->id();
        $validated['statut']          = 'brouillon';
        $validated['statut_paiement'] = 'non_paye';
        $validated['montant_paye']    = 0;
        $validated['main_oeuvre']     = $validated['main_oeuvre'] ?? 0;

        $facture = DB::transaction(function () use ($validated, $request) {
            $facture = Facture::create($validated);
            $this->saveArticles($facture, $validated);
            $facture->calculerTotaux();
            return $facture;
        });

        return redirect()->route('admin.factures.show', $facture)
            ->with('success', 'Facture créée avec succès ! Numéro : ' . $facture->numero);
    }

    public function show(Facture $facture)
    {
        $facture->load('client', 'devis', 'articles.article', 'categories', 'createdBy', 'updatedBy');
        return view('admin.factures.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        if ($facture->statut !== 'brouillon') {
            return back()->with('error', 'Seules les factures brouillons peuvent être modifiées.');
        }

        $clients = Client::actifs()->get();
        $articles = Article::actifs()->get();
        $categoriesExistantes = Category::actifs()->ordered()->get();
        $facture->load('articles', 'categories');

        return view('admin.factures.edit', compact('facture', 'clients', 'articles', 'categoriesExistantes'));
    }

    public function update(Request $request, Facture $facture)
    {
        if ($facture->statut !== 'brouillon') {
            return back()->with('error', 'Seules les factures brouillons peuvent être modifiées.');
        }

        $validated = $request->validate([
            'titre'               => 'required|string|max:255',
            'client_id'           => 'required|exists:clients,id',
            'type'                => 'required|in:provisoire,final',
            'devise'              => 'required|in:FCFA,EUR,USD',
            'date_emission'       => 'required|date',
            'date_echeance'       => 'required|date|after:date_emission',
            'introduction'        => 'nullable|string',
            'conclusion'          => 'nullable|string',
            'conditions_paiement' => 'nullable|string',
            'main_oeuvre'         => 'nullable|numeric|min:0',

            'categories'           => 'nullable|array',
            'categories.*.nom'     => 'required_with:categories|string',
            'categories.*.main_oeuvre' => 'nullable|numeric|min:0',
            'categories.*.articles'    => 'nullable|array',
            'categories.*.articles.*.article_id'       => 'required_with:categories.*.articles|string',
            'categories.*.articles.*.nouveau_nom'      => 'nullable|required_if:categories.*.articles.*.article_id,new|string|max:255',
            'categories.*.articles.*.unite'            => 'nullable|string|max:50',
            'categories.*.articles.*.quantite'         => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.prix_unitaire_ht' => 'required_with:categories.*.articles|numeric|min:0',
            'categories.*.articles.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',

            'articles_sans_categorie' => 'nullable|array',
            'articles_sans_categorie.*.article_id'       => 'required_with:articles_sans_categorie|string',
            'articles_sans_categorie.*.nouveau_nom'      => 'nullable|required_if:articles_sans_categorie.*.article_id,new|string|max:255',
            'articles_sans_categorie.*.unite'            => 'nullable|string|max:50',
            'articles_sans_categorie.*.quantite'         => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.prix_unitaire_ht' => 'required_with:articles_sans_categorie|numeric|min:0',
            'articles_sans_categorie.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $hasArticles = !empty($validated['categories']) || !empty($validated['articles_sans_categorie']);
        if (!$hasArticles) {
            return back()->withErrors(['articles' => 'Veuillez ajouter au moins un article à la facture.'])->withInput();
        }

        $validated['main_oeuvre'] = $validated['main_oeuvre'] ?? 0;
        $validated['updated_by']  = auth()->id();

        DB::transaction(function () use ($facture, $validated) {
            $facture->update($validated);

            // Supprimer les anciennes lignes
            $facture->categories()->delete();
            $facture->articles()->delete();

            $this->saveArticles($facture, $validated);
            $facture->calculerTotaux();
        });

        return redirect()->route('admin.factures.show', $facture)
            ->with('success', 'Facture mise à jour avec succès !');
    }

    public function destroy(Facture $facture)
    {
        if ($facture->statut !== 'brouillon') {
            return back()->with('error', 'Seules les factures brouillons peuvent être supprimées.');
        }

        $facture->delete();

        return redirect()->route('admin.factures.index')
            ->with('success', 'Facture supprimée avec succès !');
    }

    public function send(Request $request, Facture $facture)
    {
        $request->validate([
            'email_destinataire' => 'required|email',
            'message_personnalise' => 'nullable|string|max:2000',
        ]);

        $facture->load('client', 'articles.article', 'categories');

        Mail::to($request->email_destinataire)
            ->send(new FactureMail($facture, $request->message_personnalise));

        // Ne changer le statut que si brouillon
        if ($facture->statut === 'brouillon') {
            $facture->update([
                'statut'     => 'envoye',
                'date_envoi' => now(),
            ]);
        } else {
            $facture->update(['date_envoi' => now()]);
        }

        // Créer une notification interne
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'facture_envoyee',
            'titre'   => 'Facture envoyée',
            'message' => "La facture {$facture->numero} a été envoyée à {$request->email_destinataire}.",
            'lien'    => route('admin.factures.show', $facture),
            'icone'   => 'receipt_long',
            'couleur' => 'green',
        ]);

        return back()->with('success', 'Facture envoyée par email avec succès !');
    }

    public function registerPayment(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01|max:' . $facture->reste_a_payer,
        ]);

        $nouveauMontant = $facture->montant_paye + $validated['montant'];

        $statut = $nouveauMontant >= $facture->total_ttc ? 'paye' : 'partiel';

        $facture->update([
            'montant_paye'    => $nouveauMontant,
            'statut_paiement' => $statut,
        ]);

        return back()->with('success', 'Paiement enregistré avec succès !');
    }

    public function generatePDF(Facture $facture)
    {
        $facture->load('client', 'articles.article', 'categories');
        $parametre = \App\Models\Parametre::get();

        $pdf = Pdf::loadView('admin.factures.pdf', compact('facture', 'parametre'));

        return $pdf->download('Facture-' . $facture->numero . '.pdf');
    }

    private function saveArticles(Facture $facture, array $validated): void
    {
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

                $factureCategory = $facture->categories()->create([
                    'nom'         => $catData['nom'],
                    'ordre'       => $ordre,
                    'main_oeuvre' => $catData['main_oeuvre'] ?? 0,
                ]);

                if (!empty($catData['articles'])) {
                    foreach ($catData['articles'] as $artOrdre => $artData) {
                        [$articleId, $designation] = $this->resolveArticle($artData, $categoryModel->id);

                        $facture->articles()->create([
                            'facture_category_id' => $factureCategory->id,
                            'article_id'          => $articleId,
                            'designation'         => $designation,
                            'unite'               => $artData['unite'],
                            'quantite'            => $artData['quantite'],
                            'prix_unitaire_ht'    => $artData['prix_unitaire_ht'],
                            'remise_pourcentage'  => $artData['remise_pourcentage'] ?? 0,
                            'ordre'               => $artOrdre,
                        ]);
                    }
                }
            }
        }

        if (!empty($validated['articles_sans_categorie'])) {
            foreach ($validated['articles_sans_categorie'] as $ordre => $artData) {
                [$articleId, $designation] = $this->resolveArticle($artData);

                $facture->articles()->create([
                    'facture_category_id' => null,
                    'article_id'          => $articleId,
                    'designation'         => $designation,
                    'unite'               => $artData['unite'],
                    'quantite'            => $artData['quantite'],
                    'prix_unitaire_ht'    => $artData['prix_unitaire_ht'],
                    'remise_pourcentage'  => $artData['remise_pourcentage'] ?? 0,
                    'ordre'               => $ordre,
                ]);
            }
        }
    }

    private function resolveArticle(array $artData, ?int $categoryId = null): array
    {
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
            if ($categoryId) {
                $newArticle->categories()->attach($categoryId);
            }
            return [$newArticle->id, $newArticle->nom];
        }

        $article = Article::findOrFail($artData['article_id']);
        return [$article->id, $article->nom];
    }
}
