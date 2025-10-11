@extends('admin.layouts.app')
@section('title', 'Modifier ' . $facture->numero)
@section('content')

    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.factures.show', $facture) }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold">Modifier {{ $facture->numero }}</h2>
                    <p class="text-xs md:text-sm text-gray-500">{{ $facture->titre }}</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.factures.update', $facture) }}" method="POST" id="editForm">
            @csrf @method('PUT')

            <div class="p-4 md:p-8 max-w-5xl mx-auto space-y-6">

                <!-- Informations générales -->
                <div class="bg-white rounded-xl shadow-md p-6 space-y-5">
                    <h3 class="text-xl font-bold">Informations</h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" value="{{ old('titre', $facture->titre) }}" required
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full px-4 py-3 border rounded-lg">
                                <option value="provisoire"
                                    {{ old('type', $facture->type) === 'provisoire' ? 'selected' : '' }}>Provisoire</option>
                                <option value="final" {{ old('type', $facture->type) === 'final' ? 'selected' : '' }}>Final
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Devise <span class="text-red-500">*</span></label>
                            <select name="devise" id="devise-select" required class="w-full px-4 py-3 border rounded-lg">
                                <option value="FCFA" {{ old('devise', $facture->devise) === 'FCFA' ? 'selected' : '' }}>
                                    FCFA</option>
                                <option value="EUR" {{ old('devise', $facture->devise) === 'EUR' ? 'selected' : '' }}>EUR
                                </option>
                                <option value="USD" {{ old('devise', $facture->devise) === 'USD' ? 'selected' : '' }}>
                                    USD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Client -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Client <span class="text-red-500">*</span></label>
                        <input type="hidden" name="client_id" id="selected_client_id"
                            value="{{ old('client_id', $facture->client_id) }}">
                        <div class="p-3 border-2 border-green-500 rounded-lg bg-green-50 mb-2 flex items-center gap-3">
                            <span class="material-icons text-green-600">person</span>
                            <span class="font-medium" id="selected-client-label">{{ $facture->client->nom_complet }}</span>
                            <button type="button" onclick="toggleClientSelector()"
                                class="ml-auto text-xs text-blue-600 underline">Changer</button>
                        </div>
                        <div id="client-selector" class="hidden border rounded-xl p-4 bg-white">
                            <input type="text" placeholder="Rechercher..." onkeyup="searchClients()"
                                class="w-full px-3 py-2 border rounded-lg mb-3 text-sm">
                            <div class="space-y-2 max-h-52 overflow-y-auto">
                                @foreach ($clients as $client)
                                    <div class="client-item flex items-center gap-3 p-3 hover:bg-green-50 border hover:border-green-400 rounded-lg cursor-pointer"
                                        onclick="selectClient({{ $client->id }}, '{{ addslashes($client->nom_complet) }}')">
                                        <div
                                            class="w-9 h-9 bg-green-600 rounded-full text-white text-sm font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($client->nom_complet, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">{{ $client->nom_complet }}</p>
                                            <p class="text-xs text-gray-500">{{ $client->telephone_principal }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Date d'émission <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="date_emission"
                                value="{{ old('date_emission', $facture->date_emission->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date d'échéance <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="date_echeance"
                                value="{{ old('date_echeance', $facture->date_echeance->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 border rounded-lg">
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
                        <label class="block text-sm font-medium mb-1">Conditions de paiement</label>
                        <input type="text" name="conditions_paiement"
                            value="{{ old('conditions_paiement', $facture->conditions_paiement) }}"
                            class="w-full px-4 py-3 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Introduction</label>
                        <textarea name="introduction" rows="3" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('introduction', $facture->introduction) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Conclusion</label>
                        <textarea name="conclusion" rows="3" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('conclusion', $facture->conclusion) }}</textarea>
                    </div>
                </div>

                <!-- Articles -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold mb-3">Articles et catégories</h3>

                    <!-- Légende des colonnes -->
                    <div class="hidden md:grid grid-cols-12 gap-2 px-3 mb-2 text-xs font-semibold text-gray-500 uppercase">
                        <div class="col-span-4">Article</div>
                        <div class="col-span-2">Unité</div>
                        <div class="col-span-2">Quantité</div>
                        <div class="col-span-2">Prix HT</div>
                        <div class="col-span-1">Remise %</div>
                        <div class="col-span-1"></div>
                    </div>

                    <button type="button" onclick="addCategory()"
                        class="w-full p-4 border-2 border-dashed border-green-300 rounded-xl text-green-600 hover:bg-green-50 font-medium mb-5">
                        + Ajouter une catégorie
                    </button>

                    <div id="categories-container" class="space-y-6 mb-5">
                        @foreach ($facture->categories as $cat)
                            @php $cIdx = $loop->index + 1; @endphp
                            <div class="category-item border-2 border-gray-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3 flex-1">
                                        <span class="material-icons text-green-600">category</span>
                                        <input type="text" name="categories[{{ $cIdx }}][nom]"
                                            list="categories-list" value="{{ $cat->nom }}"
                                            class="flex-1 text-lg font-bold border-b-2 border-transparent hover:border-gray-300 focus:border-green-600 focus:outline-none px-2 py-1"
                                            required>
                                    </div>
                                    <button type="button" onclick="deleteCategory(this)"
                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </div>
                                <div class="articles-list space-y-3 mb-4" data-cat="{{ $cIdx }}">
                                    @foreach ($cat->articles as $art)
                                        @php $aIdx = $loop->index; @endphp
                                        <div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border">
                                            <div class="col-span-12 md:col-span-4">
                                                <select
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][article_id]"
                                                    class="w-full px-2 py-2 border rounded-lg text-sm article-select"
                                                    onchange="fillArticleData(this)">
                                                    <option value="{{ $art->article_id ?? 'new' }}" selected>
                                                        {{ $art->designation }}</option>
                                                    @foreach ($articles as $a)
                                                        <option value="{{ $a->id }}"
                                                            data-prix="{{ $a->prix_ht }}"
                                                            data-unite="{{ $a->unite }}">{{ $a->nom }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text"
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][nouveau_nom]"
                                                    value="{{ $art->designation }}"
                                                    class="hidden w-full mt-1 px-2 py-2 border rounded-lg text-sm new-article-name">
                                            </div>
                                            <div class="col-span-6 md:col-span-2">
                                                <input type="text"
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][unite]"
                                                    value="{{ $art->unite }}" placeholder="Unité"
                                                    class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                            </div>
                                            <div class="col-span-6 md:col-span-2">
                                                <input type="number"
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][quantite]"
                                                    value="{{ $art->quantite }}" placeholder="Qté" step="0.01"
                                                    min="0" class="w-full px-2 py-2 border rounded-lg text-sm"
                                                    required>
                                            </div>
                                            <div class="col-span-6 md:col-span-2">
                                                <input type="number"
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][prix_unitaire_ht]"
                                                    value="{{ $art->prix_unitaire_ht }}" placeholder="Prix HT"
                                                    step="0.01" min="0"
                                                    class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                            </div>
                                            <div class="col-span-5 md:col-span-1">
                                                <input type="number"
                                                    name="categories[{{ $cIdx }}][articles][{{ $aIdx }}][remise_pourcentage]"
                                                    value="{{ $art->remise_pourcentage ?? 0 }}" placeholder="Remise %"
                                                    min="0" max="100"
                                                    class="w-full px-2 py-2 border rounded-lg text-sm"
                                                    title="Remise en pourcentage appliquée sur le prix unitaire">
                                            </div>
                                            <div class="col-span-1 flex items-center justify-center">
                                                <button type="button" onclick="this.closest('.grid').remove()"
                                                    class="text-red-500 hover:bg-red-100 p-1 rounded-lg">
                                                    <span class="material-icons">close</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" onclick="addArticle({{ $cIdx }})"
                                    class="w-full p-3 border-2 border-dashed rounded-lg text-green-600 hover:bg-green-50 mb-4">
                                    + Ajouter un article
                                </button>
                                <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                    <span class="material-icons text-amber-600">build</span>
                                    <label class="text-sm font-medium flex-1">Main d'œuvre catégorie</label>
                                    <input type="number" name="categories[{{ $cIdx }}][main_oeuvre]"
                                        value="{{ $cat->main_oeuvre ?? 0 }}" step="1"
                                        class="w-32 px-3 py-2 border rounded-lg text-sm">
                                    <span class="text-sm">FCFA</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Articles sans catégorie -->
                    <div class="bg-gray-50 rounded-xl p-5">
                        <h4 class="font-bold mb-4">Articles sans catégorie</h4>
                        <div id="articles-sans-cat" class="space-y-3">
                            @foreach ($facture->articles()->whereNull('facture_category_id')->get() as $art)
                                @php $scIdx = $loop->index; @endphp
                                <div class="grid grid-cols-12 gap-2 p-3 bg-white rounded-lg border">
                                    <div class="col-span-12 md:col-span-4">
                                        <select name="articles_sans_categorie[{{ $scIdx }}][article_id]"
                                            class="w-full px-2 py-2 border rounded-lg text-sm article-select"
                                            onchange="fillArticleData(this)">
                                            <option value="{{ $art->article_id ?? 'new' }}" selected>
                                                {{ $art->designation }}</option>
                                            @foreach ($articles as $a)
                                                <option value="{{ $a->id }}" data-prix="{{ $a->prix_ht }}"
                                                    data-unite="{{ $a->unite }}">{{ $a->nom }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text"
                                            name="articles_sans_categorie[{{ $scIdx }}][nouveau_nom]"
                                            value="{{ $art->designation }}"
                                            class="hidden w-full mt-1 px-2 py-2 border rounded-lg text-sm new-article-name">
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="text" name="articles_sans_categorie[{{ $scIdx }}][unite]"
                                            value="{{ $art->unite }}" placeholder="Unité"
                                            class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="number"
                                            name="articles_sans_categorie[{{ $scIdx }}][quantite]"
                                            value="{{ $art->quantite }}" placeholder="Qté" step="0.01"
                                            min="0" class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                    </div>
                                    <div class="col-span-6 md:col-span-2">
                                        <input type="number"
                                            name="articles_sans_categorie[{{ $scIdx }}][prix_unitaire_ht]"
                                            value="{{ $art->prix_unitaire_ht }}" placeholder="Prix HT" step="0.01"
                                            min="0" class="w-full px-2 py-2 border rounded-lg text-sm" required>
                                    </div>
                                    <div class="col-span-5 md:col-span-1">
                                        <input type="number"
                                            name="articles_sans_categorie[{{ $scIdx }}][remise_pourcentage]"
                                            value="{{ $art->remise_pourcentage ?? 0 }}" placeholder="Remise %"
                                            min="0" max="100"
                                            class="w-full px-2 py-2 border rounded-lg text-sm"
                                            title="Remise en pourcentage appliquée sur le prix unitaire">
                                    </div>
                                    <div class="col-span-1 flex items-center justify-center">
                                        <button type="button" onclick="this.closest('.grid').remove()"
                                            class="text-red-500 hover:bg-red-100 p-1 rounded-lg">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addArticleSansCat()"
                            class="w-full p-3 border-2 border-dashed rounded-lg text-green-600 hover:bg-green-50 mt-3">
                            + Ajouter un article
                        </button>
                    </div>

                    <p class="text-xs text-gray-400 mt-2 mb-3 italic"><span
                            class="material-icons text-xs align-middle">info</span> Le champ "Remise %" correspond au
                        pourcentage de réduction appliqué sur le prix unitaire de l'article (ex: 10 = 10% de remise).</p>
                    <div class="mt-2 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-center gap-4">
                        <span class="material-icons text-amber-600">build</span>
                        <label class="flex-1 text-sm font-medium">Main d'œuvre globale (HT)</label>
                        <input type="number" name="main_oeuvre" value="{{ old('main_oeuvre', $facture->main_oeuvre) }}"
                            step="1" min="0" class="w-40 px-3 py-2 border rounded-lg text-sm">
                        <span class="text-sm">FCFA</span>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <a href="{{ route('admin.factures.show', $facture) }}"
                        class="px-6 py-3 border-2 rounded-lg hover:bg-gray-100">Annuler</a>
                    <button type="submit"
                        class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold">
                        <span class="material-icons text-sm">save</span> Enregistrer
                    </button>
                </div>

            </div>

            <datalist id="categories-list">
                @foreach ($categoriesExistantes as $cat)
                    <option value="{{ $cat->nom }}">{{ $cat->nom }}</option>
                @endforeach
            </datalist>
        </form>
    </div>

    <script>
        var catCount = {{ $facture->categories->count() }};
        var artCounters = {
            @foreach ($facture->categories as $cat)
                {{ $loop->index + 1 }}: {{ $cat->articles->count() }},
            @endforeach
        };
        var artSansCatCount = {{ $facture->articles()->whereNull('facture_category_id')->count() }};
        var articlesData = [
            @foreach ($articles as $article)
                {
                    id: {{ $article->id }},
                    nom: "{{ addslashes($article->nom) }}",
                    prix_ht: {{ $article->prix_ht ?? 0 }},
                    unite: "{{ addslashes($article->unite ?? '') }}",
                    prix_modifiable: {{ $article->prix_modifiable ? 1 : 0 }}
                }
                {{ $loop->last ? '' : ',' }}
            @endforeach
        ];

        function toggleClientSelector() {
            var el = document.getElementById('client-selector');
            el.classList.toggle('hidden');
        }

        function selectClient(id, nom) {
            document.getElementById('selected_client_id').value = id;
            document.getElementById('selected-client-label').textContent = nom;
            document.getElementById('client-selector').classList.add('hidden');
        }

        function searchClients() {
            var val = event.target.value.toLowerCase();
            document.querySelectorAll('#client-selector .client-item').forEach(function(item) {
                item.style.display = item.textContent.toLowerCase().includes(val) ? 'flex' : 'none';
            });
        }

        function deleteCategory(btn) {
            if (confirm('Supprimer cette catégorie ?')) btn.closest('.category-item').remove();
        }

        function buildArticleOptions() {
            var opts = '<option value="">-- Choisir --</option><option value="new">➕ Nouvel article</option>';
            articlesData.forEach(function(a) {
                opts += '<option value="' + a.id + '" data-prix="' + a.prix_ht + '" data-unite="' + a.unite +
                    '" data-modifiable="' + a.prix_modifiable + '">' + a.nom + '</option>';
            });
            return opts;
        }

        function addCategory() {
            catCount++;
            var html = '<div class="category-item border-2 border-gray-200 rounded-xl p-5">' +
                '<div class="flex items-center justify-between mb-4">' +
                '<div class="flex items-center gap-3 flex-1"><span class="material-icons text-green-600">category</span>' +
                '<input type="text" name="categories[' + catCount +
                '][nom]" list="categories-list" placeholder="Nom de la catégorie" class="flex-1 text-lg font-bold border-b-2 border-transparent focus:border-green-600 focus:outline-none px-2 py-1" required>' +
                '</div><button type="button" onclick="deleteCategory(this)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg"><span class="material-icons">delete</span></button></div>' +
                '<div class="articles-list space-y-3 mb-4" data-cat="' + catCount + '"></div>' +
                '<button type="button" onclick="addArticle(' + catCount +
                ')" class="w-full p-3 border-2 border-dashed rounded-lg text-green-600 hover:bg-green-50 mb-4">+ Ajouter un article</button>' +
                '<div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200">' +
                '<span class="material-icons text-amber-600">build</span><label class="text-sm font-medium flex-1">Main d\'œuvre</label>' +
                '<input type="number" name="categories[' + catCount +
                '][main_oeuvre]" placeholder="0" step="1" class="w-32 px-3 py-2 border rounded-lg text-sm">' +
                '<span class="text-sm">FCFA</span></div></div>';
            document.getElementById('categories-container').insertAdjacentHTML('beforeend', html);
        }

        function addArticle(catId) {
            if (!artCounters[catId]) artCounters[catId] = 0;
            var artIdx = artCounters[catId]++;
            var prefix = 'categories[' + catId + '][articles][' + artIdx + ']';
            var html = '<div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border">' +
                '<div class="col-span-12 md:col-span-4"><select name="' + prefix +
                '[article_id]" class="w-full px-2 py-2 border rounded-lg text-sm article-select" onchange="fillArticleData(this)" required>' +
                buildArticleOptions() + '</select>' +
                '<input type="text" name="' + prefix +
                '[nouveau_nom]" placeholder="Nom" class="hidden w-full mt-1 px-2 py-2 border rounded-lg text-sm new-article-name"></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="text" name="' + prefix +
                '[unite]" placeholder="Unité" class="w-full px-2 py-2 border rounded-lg text-sm article-unite" required></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix +
                '[quantite]" placeholder="Qté" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm" required></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix +
                '[prix_unitaire_ht]" placeholder="Prix HT" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm article-prix" required></div>' +
                '<div class="col-span-5 md:col-span-1"><input type="number" name="' + prefix +
                '[remise_pourcentage]" value="0" placeholder="Remise %" min="0" max="100" class="w-full px-2 py-2 border rounded-lg text-sm" title="Remise en pourcentage appliquée sur le prix unitaire"></div>' +
                '<div class="col-span-1 flex items-center justify-center"><button type="button" onclick="this.closest(\'.grid\').remove()" class="text-red-500 hover:bg-red-100 p-1 rounded-lg"><span class="material-icons">close</span></button></div></div>';
            var container = document.querySelector('[data-cat="' + catId + '"]');
            if (container) container.insertAdjacentHTML('beforeend', html);
        }

        function addArticleSansCat() {
            var artIdx = artSansCatCount++;
            var prefix = 'articles_sans_categorie[' + artIdx + ']';
            var html = '<div class="grid grid-cols-12 gap-2 p-3 bg-white rounded-lg border">' +
                '<div class="col-span-12 md:col-span-4"><select name="' + prefix +
                '[article_id]" class="w-full px-2 py-2 border rounded-lg text-sm article-select" onchange="fillArticleData(this)" required>' +
                buildArticleOptions() + '</select>' +
                '<input type="text" name="' + prefix +
                '[nouveau_nom]" placeholder="Nom" class="hidden w-full mt-1 px-2 py-2 border rounded-lg text-sm new-article-name"></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="text" name="' + prefix +
                '[unite]" placeholder="Unité" class="w-full px-2 py-2 border rounded-lg text-sm article-unite" required></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix +
                '[quantite]" placeholder="Qté" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm" required></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix +
                '[prix_unitaire_ht]" placeholder="Prix HT" step="0.01" min="0" class="w-full px-2 py-2 border rounded-lg text-sm article-prix" required></div>' +
                '<div class="col-span-5 md:col-span-1"><input type="number" name="' + prefix +
                '[remise_pourcentage]" value="0" placeholder="Remise %" min="0" max="100" class="w-full px-2 py-2 border rounded-lg text-sm" title="Remise en pourcentage appliquée sur le prix unitaire"></div>' +
                '<div class="col-span-1 flex items-center justify-center"><button type="button" onclick="this.closest(\'.grid\').remove()" class="text-red-500 hover:bg-red-100 p-1 rounded-lg"><span class="material-icons">close</span></button></div></div>';
            document.getElementById('articles-sans-cat').insertAdjacentHTML('beforeend', html);
        }

        function fillArticleData(select) {
            var row = select.closest('.grid');
            var option = select.options[select.selectedIndex];
            var newNameInput = row.querySelector('.new-article-name');
            var uniteInput = row.querySelector('.article-unite');
            var prixInput = row.querySelector('.article-prix');
            if (select.value === 'new') {
                if (newNameInput) {
                    newNameInput.classList.remove('hidden');
                    newNameInput.setAttribute('required', '');
                }
                if (prixInput) {
                    prixInput.value = '';
                    prixInput.removeAttribute('readonly');
                    prixInput.classList.remove('bg-gray-100');
                }
            } else if (select.value) {
                if (newNameInput) {
                    newNameInput.classList.add('hidden');
                    newNameInput.removeAttribute('required');
                    newNameInput.value = '';
                }
                if (uniteInput) uniteInput.value = option.dataset.unite || '';
                if (prixInput) {
                    prixInput.value = option.dataset.prix || '';
                    if (option.dataset.modifiable === '0') {
                        prixInput.setAttribute('readonly', true);
                        prixInput.classList.add('bg-gray-100');
                    } else {
                        prixInput.removeAttribute('readonly');
                        prixInput.classList.remove('bg-gray-100');
                    }
                }
            }
        }

    </script>
@endsection
