@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.devis.show', $devis) }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Modifier Devis</h2>
                        <p class="text-xs md:text-sm text-gray-500">{{ $devis->numero }} - {{ $devis->titre }}</p>
                    </div>
                </div>
                @if ($devis->statut !== 'brouillon')
                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                        Non modifiable ({{ ucfirst($devis->statut) }})
                    </span>
                @endif
            </div>
        </div>

        @if ($devis->statut !== 'brouillon')
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="material-icons text-red-600">lock</span>
                    <div>
                        <p class="font-medium text-red-800">Ce devis ne peut plus être modifié</p>
                        <p class="text-sm text-red-700">Statut actuel : {{ ucfirst($devis->statut) }}. Seuls les devis en brouillon peuvent être modifiés.</p>
                    </div>
                </div>
            </div>
        @else
            <form action="{{ route('admin.devis.update', $devis) }}" method="POST" class="p-4 md:p-8" id="editDevisForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="client_id" value="{{ $devis->client_id }}">

                <div class="max-w-5xl mx-auto space-y-6">

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <p class="font-medium text-red-800 mb-2">Erreurs de validation :</p>
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Client (lecture seule) -->
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Client</h3>
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-14 h-14 {{ $devis->client->type === 'societe' ? 'bg-purple-600' : 'bg-green-600' }} rounded-full text-white font-bold flex items-center justify-center">
                                {{ strtoupper(substr($devis->client->nom_complet, 0, 2)) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $devis->client->nom_complet }}</p>
                                <p class="text-sm text-gray-600">{{ $devis->client->email }} • {{ $devis->client->telephone_principal }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Le client ne peut pas être modifié après création</p>
                    </div>

                    <!-- Informations de base -->
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 space-y-5">
                        <h3 class="text-lg font-bold text-gray-800">Informations du devis</h3>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800">Type : <strong>{{ ucfirst($devis->type) }}</strong></p>
                            <p class="text-xs text-blue-600 mt-1">Le type ne peut pas être modifié</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Titre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="titre" id="edit_titre" value="{{ old('titre', $devis->titre) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titre') border-red-500 @enderror">
                            @error('titre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Devise <span class="text-red-500">*</span>
                                </label>
                                <select name="devise" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="FCFA" {{ old('devise', $devis->devise) === 'FCFA' ? 'selected' : '' }}>FCFA (Franc CFA)</option>
                                    <option value="EUR" {{ old('devise', $devis->devise) === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="USD" {{ old('devise', $devis->devise) === 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Validité du devis <span class="text-red-500">*</span>
                                </label>
                                <select name="validite_mois" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1"  {{ old('validite_mois', $devis->validite_mois) == 1  ? 'selected' : '' }}>1 mois</option>
                                    <option value="2"  {{ old('validite_mois', $devis->validite_mois) == 2  ? 'selected' : '' }}>2 mois</option>
                                    <option value="3"  {{ old('validite_mois', $devis->validite_mois) == 3  ? 'selected' : '' }}>3 mois</option>
                                    <option value="6"  {{ old('validite_mois', $devis->validite_mois) == 6  ? 'selected' : '' }}>6 mois</option>
                                    <option value="12" {{ old('validite_mois', $devis->validite_mois) == 12 ? 'selected' : '' }}>12 mois</option>
                                </select>
                            </div>
                        </div>

                        <!-- Info TPS/CSS -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
                            <span class="material-icons text-amber-600 mt-0.5">info</span>
                            <div class="text-sm text-amber-800">
                                <p class="font-semibold mb-1">Taxes gabonaises appliquées automatiquement :</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• <strong>TPS</strong> : 9,5% si total HT &lt; 60 000 000 FCFA — 18% si ≥ 60 000 000 FCFA</li>
                                    <li>• <strong>CSS</strong> : 1% (fixe) — La main d'œuvre n'est pas soumise aux taxes</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Introduction</label>
                            <textarea name="introduction" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('introduction', $devis->introduction) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Conclusion</label>
                            <textarea name="conclusion" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('conclusion', $devis->conclusion) }}</textarea>
                        </div>
                    </div>

                    <!-- Articles & Catégories -->
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Articles et catégories <span class="text-red-500">*</span></h3>
                            <button type="button" onclick="addCategory()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                                <span class="material-icons text-sm">add</span>
                                Ajouter une catégorie
                            </button>
                        </div>

                        <div id="categories-container" class="space-y-6 mb-6">
                            @foreach ($devis->categories as $index => $category)
                                <div class="category-item border-2 border-gray-200 rounded-xl p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3 flex-1">
                                            <span class="material-icons text-blue-600">category</span>
                                            <div class="flex-1">
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>
                                                <input type="text" name="categories[{{ $index }}][nom]"
                                                    value="{{ $category->nom }}" list="categories-list"
                                                    class="w-full text-lg font-bold border-b-2 border-transparent hover:border-gray-300 focus:border-blue-600 focus:outline-none px-2 py-1" required>
                                            </div>
                                        </div>
                                        <button type="button" onclick="this.closest('.category-item').remove()"
                                            class="text-red-600 hover:bg-red-50 p-2 rounded-lg ml-4">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>

                                    <div class="articles-list space-y-3 mb-4" data-cat="{{ $index }}">
                                        @foreach ($category->articles as $artIndex => $article)
                                            <div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <div class="col-span-12 md:col-span-4">
                                                    <select name="categories[{{ $index }}][articles][{{ $artIndex }}][article_id]"
                                                        class="w-full px-2 py-2 border rounded-lg text-sm article-select"
                                                        onchange="fillArticleData(this)" required>
                                                        <option value="">-- Choisir un article --</option>
                                                        <option value="new">➕ Créer un nouvel article</option>
                                                        @foreach ($articles as $art)
                                                            <option value="{{ $art->id }}"
                                                                {{ $article->article_id == $art->id ? 'selected' : '' }}
                                                                data-unite="{{ $art->unite }}"
                                                                data-prix="{{ $art->prix_ht }}"
                                                                data-modifiable="{{ $art->prix_modifiable ? 1 : 0 }}">
                                                                {{ $art->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="categories[{{ $index }}][articles][{{ $artIndex }}][nouveau_nom]"
                                                        class="hidden w-full px-2 py-2 border rounded-lg text-sm mt-1 new-article-name" value="">
                                                </div>
                                                <div class="col-span-6 md:col-span-2">
                                                    <input type="text" name="categories[{{ $index }}][articles][{{ $artIndex }}][unite]"
                                                        value="{{ $article->unite }}" placeholder="Unité"
                                                        class="w-full px-2 py-2 border rounded-lg text-sm article-unite">
                                                </div>
                                                <div class="col-span-6 md:col-span-2">
                                                    <input type="number" name="categories[{{ $index }}][articles][{{ $artIndex }}][quantite]"
                                                        value="{{ $article->quantite }}" step="0.01" min="0" placeholder="Qté"
                                                        class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                                </div>
                                                <div class="col-span-6 md:col-span-2">
                                                    <input type="number" name="categories[{{ $index }}][articles][{{ $artIndex }}][prix_unitaire_ht]"
                                                        value="{{ $article->prix_unitaire_ht }}" step="0.01" min="0" placeholder="Prix HT"
                                                        class="w-full px-2 py-2 border rounded-lg text-sm article-prix" required>
                                                </div>
                                                <div class="col-span-5 md:col-span-1">
                                                    <input type="number" name="categories[{{ $index }}][articles][{{ $artIndex }}][remise_pourcentage]"
                                                        value="{{ $article->remise_pourcentage }}" placeholder="%" min="0" max="100"
                                                        class="w-full px-2 py-2 border rounded-lg text-sm">
                                                </div>
                                                <div class="col-span-1 flex items-center justify-center">
                                                    <button type="button" onclick="this.closest('.grid').remove()"
                                                        class="text-red-600 hover:bg-red-100 p-2 rounded-lg">
                                                        <span class="material-icons text-lg">close</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" onclick="addArticle({{ $index }})"
                                        class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 font-medium flex items-center justify-center gap-2 transition mb-4">
                                        <span class="material-icons">add</span> Ajouter un article
                                    </button>

                                    <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                                        <div class="flex items-center gap-4">
                                            <span class="material-icons text-amber-600">build</span>
                                            <label class="text-sm font-medium flex-1">Main d'œuvre (HT)</label>
                                            <select onchange="toggleMainOeuvreMode(this, 'mo-cat-{{ $index }}')" class="px-2 py-2 border border-gray-300 rounded-lg text-sm">
                                                <option value="montant" {{ !$category->main_oeuvre_pourcentage ? 'selected' : '' }}>Montant fixe</option>
                                                <option value="pourcentage" {{ $category->main_oeuvre_pourcentage ? 'selected' : '' }}>Pourcentage</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 {{ $category->main_oeuvre_pourcentage ? 'hidden' : '' }}" id="mo-cat-{{ $index }}-montant">
                                            <input type="number" name="categories[{{ $index }}][main_oeuvre]"
                                                value="{{ $category->main_oeuvre_pourcentage ? '' : $category->main_oeuvre }}" step="0.01"
                                                class="w-32 px-3 py-2 border rounded-lg text-sm">
                                            <span class="text-sm">FCFA</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 {{ !$category->main_oeuvre_pourcentage ? 'hidden' : '' }}" id="mo-cat-{{ $index }}-pourcentage">
                                            <input type="number" name="categories[{{ $index }}][main_oeuvre_pourcentage]"
                                                value="{{ $category->main_oeuvre_pourcentage }}" step="0.01" min="0" max="100"
                                                class="w-32 px-3 py-2 border rounded-lg text-sm">
                                            <span class="text-sm">% du sous-total</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Articles sans catégorie -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="font-bold mb-4">Articles sans catégorie</h4>
                            <div id="articles-sans-cat" class="space-y-3">
                                @foreach ($devis->articles()->whereNull('devis_category_id')->get() as $artIndex => $article)
                                    <div class="grid grid-cols-12 gap-2 p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="col-span-12 md:col-span-4">
                                            <select name="articles_sans_categorie[{{ $artIndex }}][article_id]"
                                                class="w-full px-2 py-2 border rounded-lg text-sm article-select"
                                                onchange="fillArticleData(this)" required>
                                                <option value="">-- Choisir un article --</option>
                                                <option value="new">➕ Créer un nouvel article</option>
                                                @foreach ($articles as $art)
                                                    <option value="{{ $art->id }}"
                                                        {{ $article->article_id == $art->id ? 'selected' : '' }}
                                                        data-unite="{{ $art->unite }}" data-prix="{{ $art->prix_ht }}"
                                                        data-modifiable="{{ $art->prix_modifiable ? 1 : 0 }}">
                                                        {{ $art->nom }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="articles_sans_categorie[{{ $artIndex }}][nouveau_nom]"
                                                class="hidden w-full px-2 py-2 border rounded-lg text-sm mt-1 new-article-name" value="">
                                        </div>
                                        <div class="col-span-6 md:col-span-2">
                                            <input type="text" name="articles_sans_categorie[{{ $artIndex }}][unite]"
                                                value="{{ $article->unite }}" placeholder="Unité"
                                                class="w-full px-2 py-2 border rounded-lg text-sm article-unite">
                                        </div>
                                        <div class="col-span-6 md:col-span-2">
                                            <input type="number" name="articles_sans_categorie[{{ $artIndex }}][quantite]"
                                                value="{{ $article->quantite }}" step="0.01" min="0" placeholder="Qté"
                                                class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                        </div>
                                        <div class="col-span-6 md:col-span-2">
                                            <input type="number" name="articles_sans_categorie[{{ $artIndex }}][prix_unitaire_ht]"
                                                value="{{ $article->prix_unitaire_ht }}" step="0.01" min="0" placeholder="Prix HT"
                                                class="w-full px-2 py-2 border rounded-lg text-sm article-prix" required>
                                        </div>
                                        <div class="col-span-5 md:col-span-1">
                                            <input type="number" name="articles_sans_categorie[{{ $artIndex }}][remise_pourcentage]"
                                                value="{{ $article->remise_pourcentage }}" placeholder="%" min="0" max="100"
                                                class="w-full px-2 py-2 border rounded-lg text-sm">
                                        </div>
                                        <div class="col-span-1 flex items-center justify-center">
                                            <button type="button" onclick="this.closest('.grid').remove()"
                                                class="text-red-600 hover:bg-red-100 p-2 rounded-lg">
                                                <span class="material-icons text-lg">close</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addArticleSansCat()"
                                class="w-full p-3 border-2 border-dashed rounded-lg text-blue-600 hover:bg-blue-50 mt-3">
                                + Ajouter un article
                            </button>
                            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 mt-4">
                                <div class="flex items-center gap-4">
                                    <span class="material-icons text-amber-600">build</span>
                                    <label class="text-sm font-medium text-gray-700 flex-1">Main d'œuvre hors catégorie (HT)</label>
                                    <select onchange="toggleMainOeuvreMode(this, 'mo-global')" class="px-2 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="montant" {{ !$devis->main_oeuvre_pourcentage ? 'selected' : '' }}>Montant fixe</option>
                                        <option value="pourcentage" {{ $devis->main_oeuvre_pourcentage ? 'selected' : '' }}>Pourcentage</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 mt-2 {{ $devis->main_oeuvre_pourcentage ? 'hidden' : '' }}" id="mo-global-montant">
                                    <input type="number" name="main_oeuvre"
                                        value="{{ old('main_oeuvre', $devis->main_oeuvre_pourcentage ? '' : $devis->main_oeuvre) }}" placeholder="0" step="0.01" min="0"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <span class="text-sm font-medium text-gray-700">{{ $devis->devise }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2 {{ !$devis->main_oeuvre_pourcentage ? 'hidden' : '' }}" id="mo-global-pourcentage">
                                    <input type="number" name="main_oeuvre_pourcentage"
                                        value="{{ old('main_oeuvre_pourcentage', $devis->main_oeuvre_pourcentage) }}" placeholder="0" step="0.01" min="0" max="100"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <span class="text-sm font-medium text-gray-700">% du total HT</span>
                                </div>
                            </div>
                        </div>

                        <datalist id="categories-list">
                            @foreach ($categoriesExistantes as $cat)
                                <option value="{{ $cat->nom }}">{{ $cat->nom }}</option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between gap-4 sticky bottom-0 bg-white p-6 rounded-xl shadow-lg border-2 border-gray-200">
                        <a href="{{ route('admin.devis.show', $devis) }}"
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100">
                            Annuler
                        </a>
                        <button type="button" onclick="validateAndSubmit()"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 shadow-md flex items-center gap-2">
                            <span class="material-icons text-sm">save</span> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        var catCount = {{ $devis->categories->count() }};
        var artCounters = {};
        var artSansCatCount = {{ $devis->articles()->whereNull('devis_category_id')->count() }};

        @foreach ($devis->categories as $index => $category)
            artCounters[{{ $index }}] = {{ $category->articles->count() }};
        @endforeach

        var articlesData = [];
        @if (isset($articles) && $articles->count() > 0)
            articlesData = [
                @foreach ($articles as $article)
                    {
                        id: {{ $article->id }},
                        nom: "{{ addslashes($article->nom) }}",
                        prix_ht: {{ $article->prix_ht ?? 0 }},
                        unite: "{{ addslashes($article->unite ?? '') }}",
                        prix_modifiable: {{ $article->prix_modifiable ? 1 : 0 }}
                    }{{ $loop->last ? '' : ',' }}
                @endforeach
            ];
        @endif

        // ── VALIDATION AVANT SOUMISSION ────────────────────────────────────
        function validateAndSubmit() {
            var errors = [];

            // Titre obligatoire
            var titre = document.getElementById('edit_titre');
            if (!titre || !titre.value.trim()) {
                errors.push('Le titre du devis est obligatoire.');
                if (titre) titre.classList.add('border-red-500');
            } else {
                if (titre) titre.classList.remove('border-red-500');
            }

            // Au moins un article
            var hasCategories   = document.querySelectorAll('#categories-container .category-item').length > 0;
            var hasArticlesSans = document.querySelectorAll('#articles-sans-cat > div.grid').length > 0;
            if (!hasCategories && !hasArticlesSans) {
                errors.push('Veuillez ajouter au moins un article au devis.');
            }

            // Vérifier les articles dans les catégories
            document.querySelectorAll('#categories-container .article-select').forEach(function(sel) {
                if (!sel.value) errors.push('Veuillez sélectionner un article pour chaque ligne.');
            });
            document.querySelectorAll('#articles-sans-cat .article-select').forEach(function(sel) {
                if (!sel.value) errors.push('Veuillez sélectionner un article pour chaque ligne.');
            });

            if (errors.length > 0) {
                showErrors(errors);
                return;
            }

            document.getElementById('editDevisForm').submit();
        }

        function showErrors(errors) {
            var existing = document.getElementById('js-errors');
            if (existing) existing.remove();

            var html = '<div id="js-errors" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4">' +
                '<div class="flex items-center gap-2 mb-2"><span class="material-icons text-red-500">error</span>' +
                '<strong class="text-red-700">Veuillez corriger les erreurs suivantes :</strong></div>' +
                '<ul class="list-disc list-inside text-sm text-red-600 space-y-1">';
            errors.forEach(function(e) { html += '<li>' + e + '</li>'; });
            html += '</ul></div>';

            var form = document.querySelector('.max-w-5xl');
            form.insertAdjacentHTML('afterbegin', html);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ── MAIN D'OEUVRE : TOGGLE MONTANT / POURCENTAGE ────────────────────
        function toggleMainOeuvreMode(select, prefix) {
            var montantDiv = document.getElementById(prefix + '-montant');
            var pourcentageDiv = document.getElementById(prefix + '-pourcentage');
            if (select.value === 'pourcentage') {
                montantDiv.classList.add('hidden');
                pourcentageDiv.classList.remove('hidden');
                montantDiv.querySelector('input').value = '';
            } else {
                montantDiv.classList.remove('hidden');
                pourcentageDiv.classList.add('hidden');
                pourcentageDiv.querySelector('input').value = '';
            }
        }

        // ── GESTION CATÉGORIES ─────────────────────────────────────────────
        function addCategory() {
            catCount++;
            artCounters[catCount] = 0;
            var html =
                '<div class="category-item border-2 border-gray-200 rounded-xl p-6">' +
                '<div class="flex items-center justify-between mb-4">' +
                '<div class="flex items-center gap-3 flex-1"><span class="material-icons text-blue-600">category</span>' +
                '<div class="flex-1"><label class="block text-xs font-medium text-gray-500 mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>' +
                '<input type="text" name="categories[' + catCount + '][nom]" list="categories-list" placeholder="Nom catégorie" class="w-full text-lg font-bold border-b-2 border-transparent hover:border-gray-300 focus:border-blue-600 focus:outline-none px-2 py-1" required>' +
                '</div></div>' +
                '<button type="button" onclick="this.closest(\'.category-item\').remove()" class="text-red-600 hover:bg-red-50 p-2 rounded-lg ml-4"><span class="material-icons">delete</span></button>' +
                '</div>' +
                '<div class="articles-list space-y-3 mb-4" data-cat="' + catCount + '"></div>' +
                '<button type="button" onclick="addArticle(' + catCount + ')" class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 font-medium flex items-center justify-center gap-2 transition mb-4">' +
                '<span class="material-icons">add</span> Ajouter un article</button>' +
                '<div class="p-3 bg-amber-50 rounded-lg border border-amber-200">' +
                '<div class="flex items-center gap-4">' +
                '<span class="material-icons text-amber-600">build</span>' +
                '<label class="text-sm font-medium flex-1">Main d\'œuvre (HT)</label>' +
                '<select onchange="toggleMainOeuvreMode(this, \'mo-cat-' + catCount + '\')" class="px-2 py-2 border border-gray-300 rounded-lg text-sm">' +
                '<option value="montant">Montant fixe</option>' +
                '<option value="pourcentage">Pourcentage</option>' +
                '</select></div>' +
                '<div class="flex items-center gap-2 mt-2" id="mo-cat-' + catCount + '-montant">' +
                '<input type="number" name="categories[' + catCount + '][main_oeuvre]" step="0.01" class="w-32 px-3 py-2 border rounded-lg text-sm" placeholder="0">' +
                '<span class="text-sm">FCFA</span></div>' +
                '<div class="flex items-center gap-2 mt-2 hidden" id="mo-cat-' + catCount + '-pourcentage">' +
                '<input type="number" name="categories[' + catCount + '][main_oeuvre_pourcentage]" step="0.01" min="0" max="100" class="w-32 px-3 py-2 border rounded-lg text-sm" placeholder="0">' +
                '<span class="text-sm">% du sous-total</span></div>' +
                '</div></div>';
            document.getElementById('categories-container').insertAdjacentHTML('beforeend', html);
        }

        function buildArticleOptions() {
            var opts = '<option value="">-- Choisir un article --</option><option value="new">➕ Créer un nouvel article</option>';
            for (var i = 0; i < articlesData.length; i++) {
                var a = articlesData[i];
                opts += '<option value="' + a.id + '" data-unite="' + a.unite + '" data-prix="' + a.prix_ht + '" data-modifiable="' + a.prix_modifiable + '">' + a.nom + '</option>';
            }
            return opts;
        }

        function buildArticleRow(prefix) {
            return '<div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">' +
                '<div class="col-span-12 md:col-span-4">' +
                '<select name="' + prefix + '[article_id]" class="w-full px-2 py-2 border rounded-lg text-sm article-select" onchange="fillArticleData(this)" required>' +
                buildArticleOptions() + '</select>' +
                '<input type="text" name="' + prefix + '[nouveau_nom]" placeholder="Nom du nouvel article" class="hidden w-full px-2 py-2 border rounded-lg text-sm mt-1 new-article-name">' +
                '</div>' +
                '<div class="col-span-6 md:col-span-2"><input type="text" name="' + prefix + '[unite]" placeholder="Unité" class="w-full px-2 py-2 border rounded-lg text-sm article-unite"></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[quantite]" placeholder="Qté" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm" required></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[prix_unitaire_ht]" placeholder="Prix HT" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm article-prix" required></div>' +
                '<div class="col-span-5 md:col-span-1"><input type="number" name="' + prefix + '[remise_pourcentage]" placeholder="%" min="0" max="100" value="0" class="w-full px-2 py-2 border rounded-lg text-sm"></div>' +
                '<div class="col-span-1 flex items-center justify-center"><button type="button" onclick="this.closest(\'.grid\').remove()" class="text-red-600 hover:bg-red-100 p-2 rounded-lg"><span class="material-icons text-lg">close</span></button></div>' +
                '</div>';
        }

        function addArticle(catId) {
            if (!artCounters[catId]) artCounters[catId] = 0;
            var artIdx = artCounters[catId]++;
            var prefix = 'categories[' + catId + '][articles][' + artIdx + ']';
            var container = document.querySelector('[data-cat="' + catId + '"]');
            if (container) container.insertAdjacentHTML('beforeend', buildArticleRow(prefix));
        }

        function addArticleSansCat() {
            var artIdx = artSansCatCount++;
            var prefix = 'articles_sans_categorie[' + artIdx + ']';
            var row = buildArticleRow(prefix).replace('bg-gray-50', 'bg-white');
            document.getElementById('articles-sans-cat').insertAdjacentHTML('beforeend', row);
        }

        function fillArticleData(select) {
            var row = select.closest('.grid');
            if (!row) return;
            var option = select.options[select.selectedIndex];
            var newNameInput = row.querySelector('.new-article-name');
            var uniteInput   = row.querySelector('.article-unite');
            var prixInput    = row.querySelector('.article-prix');

            if (select.value === 'new') {
                if (newNameInput) { newNameInput.classList.remove('hidden'); newNameInput.setAttribute('required', 'required'); }
                if (uniteInput) uniteInput.value = '';
                if (prixInput)  { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            } else if (select.value) {
                if (newNameInput) { newNameInput.classList.add('hidden'); newNameInput.removeAttribute('required'); newNameInput.value = ''; }
                if (uniteInput) uniteInput.value = option.dataset.unite || '';
                if (prixInput) {
                    prixInput.value = option.dataset.prix || '';
                    if (option.dataset.modifiable === '0') { prixInput.setAttribute('readonly', true); prixInput.classList.add('bg-gray-100'); }
                    else { prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
                }
            } else {
                if (newNameInput) { newNameInput.classList.add('hidden'); newNameInput.removeAttribute('required'); }
                if (uniteInput) uniteInput.value = '';
                if (prixInput)  { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            }
        }
    </script>
@endsection
