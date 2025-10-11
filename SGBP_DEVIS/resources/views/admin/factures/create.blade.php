@extends('admin.layouts.app')
@section('title', 'Nouvelle Facture')
@section('content')
    <style>
        .form-step { display: none; animation: fadeIn 0.4s ease; }
        .form-step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .step-circle { transition: all 0.3s ease; }
        .step-circle.active { background: #16a34a; color: white; }
        .step-circle.completed { background: #10B981; color: white; }
        .step-line { transition: all 0.3s ease; }
        .step-line.completed { background: #10B981; }
    </style>

    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.factures.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold">Nouvelle Facture</h2>
                    <p class="text-xs md:text-sm text-gray-500">3 étapes</p>
                </div>
            </div>
        </div>

        <!-- Stepper -->
        <div class="bg-white border-b px-4 md:px-8 py-6 shadow-sm">
            <div class="max-w-5xl mx-auto flex items-center justify-between">
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-1" class="step-circle active w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg">1</div>
                    <p class="text-xs font-medium text-green-600 mt-2">Client & Infos</p>
                </div>
                <div id="line-1" class="step-line h-1 flex-1 bg-gray-200 -mt-8"></div>
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-2" class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">2</div>
                    <p class="text-xs text-gray-500 mt-2">Articles</p>
                </div>
                <div id="line-2" class="step-line h-1 flex-1 bg-gray-200 -mt-8"></div>
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-3" class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">3</div>
                    <p class="text-xs text-gray-500 mt-2">Révision</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-icons text-red-500">error</span>
                    <strong class="text-red-700">Erreurs de validation :</strong>
                </div>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.factures.store') }}" method="POST" id="factureForm">
            @csrf
            <input type="hidden" name="client_id" id="selected_client_id" value="{{ old('client_id') }}">

            <div class="p-4 md:p-8 max-w-5xl mx-auto">

                <!-- STEP 1 : Client + Infos -->
                <div id="step-1" class="form-step active space-y-6">

                    <!-- Sélection client -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold">Sélection du client</h3>
                            <a href="{{ route('admin.clients.create') }}"
                                class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                <span class="material-icons text-sm">person_add</span>
                                <span class="hidden md:inline">Nouveau client</span>
                            </a>
                        </div>
                        <div class="relative mb-4">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input type="text" id="search-client" placeholder="Rechercher un client..."
                                class="w-full pl-12 pr-4 py-3 border rounded-lg" onkeyup="searchClients()">
                        </div>
                        <div class="space-y-3 max-h-80 overflow-y-auto" id="client-list">
                            @forelse($clients as $client)
                                <div class="client-item flex items-center gap-4 p-4 border-2 border-transparent hover:border-green-600 bg-white hover:bg-green-50 rounded-lg cursor-pointer transition
                                {{ old('client_id') == $client->id ? 'border-green-600 bg-green-50' : '' }}"
                                    onclick="selectClient({{ $client->id }}, '{{ addslashes($client->nom_complet) }}')">
                                    <div class="w-12 h-12 {{ $client->type === 'societe' ? 'bg-purple-600' : 'bg-green-600' }} rounded-full text-white font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($client->nom_complet, 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 flex items-center gap-2">
                                            {{ $client->nom_complet }}
                                            <span class="text-xs px-2 py-0.5 {{ $client->type === 'societe' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }} rounded-full">
                                                {{ $client->type_display }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600 flex items-center gap-3 mt-1">
                                            <span class="flex items-center gap-1">
                                                <span class="material-icons text-sm">phone</span>
                                                {{ $client->telephone_principal }}
                                            </span>
                                            @if($client->email)
                                            <span class="flex items-center gap-1">
                                                <span class="material-icons text-sm">email</span>
                                                {{ $client->email }}
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="material-icons client-check {{ old('client_id') == $client->id ? 'text-green-600' : 'text-gray-300' }}">
                                        {{ old('client_id') == $client->id ? 'check_circle' : 'radio_button_unchecked' }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center py-8 text-gray-400">Aucun client actif</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Infos facture -->
                    <div class="bg-white rounded-xl shadow-md p-6 space-y-5">
                        <h3 class="text-xl font-bold">Informations de la facture</h3>

                        <div>
                            <label class="block text-sm font-medium mb-1">Titre <span class="text-red-500">*</span></label>
                            <input type="text" name="titre" value="{{ old('titre') }}"
                                class="w-full px-4 py-3 border rounded-lg" placeholder="Ex: Travaux de rénovation">
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Type <span class="text-red-500">*</span></label>
                                <select name="type" class="w-full px-4 py-3 border rounded-lg">
                                    <option value="provisoire" {{ old('type') != 'final' ? 'selected' : '' }}>Provisoire (acompte)</option>
                                    <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Final</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Devise <span class="text-red-500">*</span></label>
                                <select name="devise" id="devise-select" class="w-full px-4 py-3 border rounded-lg">
                                    <option value="FCFA" {{ old('devise', 'FCFA') == 'FCFA' ? 'selected' : '' }}>FCFA (Franc CFA)</option>
                                    <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </select>
                            </div>
                        </div>

                        @if ($devis->count() > 0)
                            <div>
                                <label class="block text-sm font-medium mb-1">Devis lié (optionnel)</label>
                                <select name="devis_id" class="w-full px-4 py-3 border rounded-lg">
                                    <option value="">-- Aucun devis --</option>
                                    @foreach ($devis as $d)
                                        <option value="{{ $d->id }}" {{ old('devis_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->numero }} — {{ $d->titre }} ({{ $d->client->nom_complet }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Date d'émission <span class="text-red-500">*</span></label>
                                <input type="date" name="date_emission" value="{{ old('date_emission', date('Y-m-d')) }}"
                                    class="w-full px-4 py-3 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Date d'échéance <span class="text-red-500">*</span></label>
                                <input type="date" name="date_echeance" value="{{ old('date_echeance', date('Y-m-d', strtotime('+30 days'))) }}"
                                    class="w-full px-4 py-3 border rounded-lg">
                            </div>
                        </div>

                        <!-- Info TPS/CSS -->
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start gap-3">
                            <span class="material-icons text-amber-600 mt-0.5">info</span>
                            <div class="text-sm text-amber-800">
                                <p class="font-semibold mb-1">Taxes gabonaises appliquées automatiquement :</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• <strong>TPS</strong> : 9,5% appliqué sur la main d'œuvre uniquement</li>
                                    <li>• <strong>CSS</strong> : 1% appliqué sur le total HT (hors main d'œuvre)</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Conditions de paiement</label>
                            <input type="text" name="conditions_paiement" value="{{ old('conditions_paiement') }}"
                                class="w-full px-4 py-3 border rounded-lg" placeholder="Ex: 30 jours fin de mois">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Introduction</label>
                            <textarea name="introduction" rows="3" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('introduction') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Conclusion</label>
                            <textarea name="conclusion" rows="3" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('conclusion') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 : Articles -->
                <div id="step-2" class="form-step">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-4">Articles et catégories</h3>

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
                            class="w-full p-4 border-2 border-dashed border-green-300 rounded-xl text-green-600 hover:bg-green-50 font-medium mb-6">
                            + Ajouter une catégorie
                        </button>

                        <div id="categories-container" class="space-y-6 mb-6"></div>

                        <div class="bg-gray-50 rounded-xl p-5">
                            <h4 class="font-bold mb-4">Articles sans catégorie</h4>
                            <div id="articles-sans-cat" class="space-y-3"></div>
                            <button type="button" onclick="addArticleSansCat()"
                                class="w-full p-3 border-2 border-dashed rounded-lg text-green-600 hover:bg-green-50 mt-3">
                                + Ajouter un article
                            </button>
                        </div>

                        <p class="text-xs text-gray-400 mt-2 mb-4 italic">
                            <span class="material-icons text-xs align-middle">info</span>
                            Le champ "Remise %" correspond au pourcentage de réduction appliqué sur le prix unitaire.
                        </p>
                        <div class="mt-2 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-center gap-4">
                            <span class="material-icons text-amber-600">build</span>
                            <label class="flex-1 text-sm font-medium">Main d'œuvre globale (HT)</label>
                            <input type="number" name="main_oeuvre" value="{{ old('main_oeuvre', 0) }}" step="1" min="0"
                                class="w-40 px-3 py-2 border rounded-lg text-sm" placeholder="0">
                            <span class="text-sm font-medium">FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 3 : Révision -->
                <div id="step-3" class="form-step">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-6">Révision finale</h3>
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div class="p-4 bg-green-50 rounded-lg">
                                <h4 class="font-bold mb-3">Client</h4>
                                <p class="font-medium" id="recap-client-nom">—</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-bold mb-3">Informations</h4>
                                <div class="space-y-1 text-sm">
                                    <p><span class="text-gray-500">Titre :</span> <strong id="recap-titre">—</strong></p>
                                    <p><span class="text-gray-500">Type :</span> <strong id="recap-type">—</strong></p>
                                    <p><span class="text-gray-500">Devise :</span> <strong id="recap-devise">—</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Détail taxes -->
                        <div class="p-5 bg-gray-50 rounded-xl border border-gray-200 mb-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Détail du calcul</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total HT :</span>
                                    <strong id="recap-ht">0 FCFA</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">TPS (<span id="recap-taux-tps">9,5</span>%) :</span>
                                    <strong id="recap-tps">0 FCFA</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">CSS (1%) :</span>
                                    <strong id="recap-css">0 FCFA</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Main d'œuvre :</span>
                                    <strong id="recap-mo">0 FCFA</strong>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-gradient-to-br from-green-600 to-green-700 rounded-xl text-white text-center">
                            <p class="text-sm mb-1 opacity-80">Montant total TTC</p>
                            <p class="text-4xl font-bold" id="recap-total">0 FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="btn-prev" onclick="previousStep()"
                        class="px-6 py-3 border-2 rounded-lg hover:bg-gray-100" style="display:none;">← Précédent</button>
                    <button type="button" id="btn-next" onclick="nextStep()"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 ml-auto">Suivant →</button>
                    <button type="submit" id="btn-submit"
                        class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 ml-auto font-bold">
                        <span class="material-icons text-sm">check_circle</span> Créer la facture
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
        var currentStep      = 1;
        var totalSteps       = 3;
        var selectedClientId = {{ old('client_id', 'null') }};
        var selectedClientNom = '';
        var catCount         = 0;
        var artCounters      = {};
        var artSansCatCount  = 0;

        // Constantes TPS/CSS Gabon
        var SEUIL_TPS_HAUT = 60000000;
        var TAUX_TPS_BAS   = 9.5;
        var TAUX_TPS_HAUT  = 18.0;
        var TAUX_CSS       = 1.0;

        var articlesData = [
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

        // ── INLINE ERROR HELPERS ───────────────────────────────────────────
        function showStepError(stepId, message) {
            var existing = document.getElementById('step-error-' + stepId);
            if (existing) existing.remove();
            var stepEl = document.getElementById('step-' + stepId);
            if (!stepEl) return;
            var html = '<div id="step-error-' + stepId + '" class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-center gap-3">' +
                '<span class="material-icons text-red-500">error</span>' +
                '<p class="text-sm font-medium text-red-700">' + message + '</p>' +
                '</div>';
            stepEl.insertAdjacentHTML('afterbegin', html);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function clearStepError(stepId) {
            var existing = document.getElementById('step-error-' + stepId);
            if (existing) existing.remove();
        }

        function highlightField(field, hasError) {
            if (!field) return;
            if (hasError) {
                field.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                field.classList.remove('border-gray-300');
            } else {
                field.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                field.classList.add('border-gray-300');
            }
        }

        // ── VALIDATION ─────────────────────────────────────────────────────
        function validateStep(step) {
            clearStepError(step);

            if (step === 1) {
                if (!selectedClientId) {
                    showStepError(1, '⚠️ Veuillez sélectionner un client dans la liste ci-dessous.');
                    var clientList = document.getElementById('client-list');
                    if (clientList) {
                        clientList.style.border = '2px solid #ef4444';
                        clientList.style.borderRadius = '8px';
                        setTimeout(function() { clientList.style.border = ''; }, 3000);
                    }
                    return false;
                }
                var titre = document.querySelector('[name="titre"]');
                if (!titre || !titre.value.trim()) {
                    highlightField(titre, true);
                    showStepError(1, '⚠️ Le titre de la facture est obligatoire.');
                    if (titre) titre.focus();
                    return false;
                }
                highlightField(titre, false);
                var dateEmission = document.querySelector('[name="date_emission"]');
                var dateEcheance = document.querySelector('[name="date_echeance"]');
                if (!dateEmission || !dateEmission.value) {
                    highlightField(dateEmission, true);
                    showStepError(1, '⚠️ La date d\'émission est obligatoire.');
                    return false;
                }
                highlightField(dateEmission, false);
                if (!dateEcheance || !dateEcheance.value) {
                    highlightField(dateEcheance, true);
                    showStepError(1, '⚠️ La date d\'échéance est obligatoire.');
                    return false;
                }
                highlightField(dateEcheance, false);
            }

            if (step === 2) {
                var hasCategories   = document.querySelectorAll('#categories-container .category-item').length > 0;
                var hasArticlesSans = document.querySelectorAll('#articles-sans-cat > div.grid').length > 0;

                if (!hasCategories && !hasArticlesSans) {
                    showStepError(2, '⚠️ Veuillez ajouter au moins un article ou une catégorie à la facture.');
                    return false;
                }

                // Vérifier les noms de catégories
                var catNomInputs  = document.querySelectorAll('#categories-container [name*="[nom]"]');
                var missingCatNom = false;
                catNomInputs.forEach(function(f) {
                    if (!f.value.trim()) { highlightField(f, true); missingCatNom = true; }
                    else highlightField(f, false);
                });
                if (missingCatNom) {
                    showStepError(2, '⚠️ Veuillez renseigner le nom de chaque catégorie.');
                    return false;
                }

                // Vérifier les sélects d'articles
                var articleSelects = document.querySelectorAll('#categories-container .article-select, #articles-sans-cat .article-select');
                var missingArticle = false;
                articleSelects.forEach(function(sel) {
                    if (!sel.value) { highlightField(sel, true); missingArticle = true; }
                    else highlightField(sel, false);
                });

                // Vérifier le nom du nouvel article si "new" sélectionné
                var missingNewName = false;
                articleSelects.forEach(function(sel) {
                    if (sel.value === 'new') {
                        var row = sel.closest('.grid');
                        if (row) {
                            var newNameInput = row.querySelector('.new-article-name');
                            if (newNameInput && !newNameInput.value.trim()) {
                                highlightField(newNameInput, true);
                                missingNewName = true;
                            }
                        }
                    }
                });

                // Vérifier unité, quantité, prix
                var missingFields = false;
                document.querySelectorAll('#categories-container .article-unite, #articles-sans-cat .article-unite').forEach(function(f) {
                    if (!f.value) { highlightField(f, true); missingFields = true; }
                    else highlightField(f, false);
                });
                document.querySelectorAll('#categories-container [name*="[quantite]"], #articles-sans-cat [name*="[quantite]"]').forEach(function(f) {
                    if (!f.value) { highlightField(f, true); missingFields = true; }
                    else highlightField(f, false);
                });
                document.querySelectorAll('#categories-container .article-prix, #articles-sans-cat .article-prix').forEach(function(f) {
                    if (!f.value) { highlightField(f, true); missingFields = true; }
                    else highlightField(f, false);
                });

                if (missingArticle) {
                    showStepError(2, '⚠️ Veuillez sélectionner un article pour chaque ligne.');
                    return false;
                }
                if (missingNewName) {
                    showStepError(2, '⚠️ Veuillez saisir le nom du nouvel article.');
                    return false;
                }
                if (missingFields) {
                    showStepError(2, '⚠️ Veuillez remplir tous les champs obligatoires des articles (unité, quantité, prix HT).');
                    return false;
                }
            }

            return true;
        }

        // ── NAVIGATION ─────────────────────────────────────────────────────
        function nextStep() {
            if (!validateStep(currentStep)) return;
            if (currentStep < totalSteps) {
                document.getElementById('step-' + currentStep).classList.remove('active');
                currentStep++;
                document.getElementById('step-' + currentStep).classList.add('active');
                if (currentStep === totalSteps) buildRecap();
                updateStepper();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                document.getElementById('step-' + currentStep).classList.remove('active');
                currentStep--;
                document.getElementById('step-' + currentStep).classList.add('active');
                updateStepper();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function updateStepper() {
            for (var i = 1; i <= totalSteps; i++) {
                var circle = document.getElementById('circle-' + i);
                var line   = document.getElementById('line-' + i);
                if (i < currentStep) {
                    circle.className = 'step-circle completed w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg';
                    circle.innerHTML = '<span class="material-icons text-sm">check</span>';
                    if (line) line.classList.add('completed');
                } else if (i === currentStep) {
                    circle.className = 'step-circle active w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg';
                    circle.textContent = i;
                } else {
                    circle.className = 'step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold';
                    circle.textContent = i;
                    if (line) line.classList.remove('completed');
                }
            }
            var btnPrev   = document.getElementById('btn-prev');
            var btnNext   = document.getElementById('btn-next');
            var btnSubmit = document.getElementById('btn-submit');
            btnPrev.style.display = currentStep === 1 ? 'none' : 'flex';
            if (currentStep === totalSteps) {
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
            } else {
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
            }
        }

        // ── CLIENT ─────────────────────────────────────────────────────────
        function selectClient(id, nom) {
            selectedClientId  = id;
            selectedClientNom = nom;
            document.getElementById('selected_client_id').value = id;
            document.querySelectorAll('.client-item').forEach(function(item) {
                item.classList.remove('border-green-600', 'bg-green-50');
                var check = item.querySelector('.client-check');
                if (check) { check.textContent = 'radio_button_unchecked'; check.classList.remove('text-green-600'); check.classList.add('text-gray-300'); }
            });
            event.currentTarget.classList.add('border-green-600', 'bg-green-50');
            var currentCheck = event.currentTarget.querySelector('.client-check');
            if (currentCheck) { currentCheck.textContent = 'check_circle'; currentCheck.classList.add('text-green-600'); currentCheck.classList.remove('text-gray-300'); }
        }

        function searchClients() {
            var val = document.getElementById('search-client').value.toLowerCase();
            document.querySelectorAll('.client-item').forEach(function(item) {
                item.style.display = item.textContent.toLowerCase().includes(val) ? 'flex' : 'none';
            });
        }

        // ── ARTICLES ───────────────────────────────────────────────────────
        function buildArticleOptions() {
            var opts = '<option value="">-- Choisir un article --</option><option value="new">➕ Créer un nouvel article</option>';
            articlesData.forEach(function(a) {
                opts += '<option value="' + a.id + '" data-prix="' + a.prix_ht + '" data-unite="' + a.unite + '" data-modifiable="' + a.prix_modifiable + '">' + a.nom + '</option>';
            });
            return opts;
        }

        function buildUniteOptions(selectedValue) {
            var unites = ['m³', 'm²', 'ml', 'pf', 'u', 'kg', 'h', 'jour', 'forfait'];
            var opts = '<option value="">-- Unité --</option>';
            for (var i = 0; i < unites.length; i++) {
                var sel = (selectedValue && selectedValue === unites[i]) ? ' selected' : '';
                opts += '<option value="' + unites[i] + '"' + sel + '>' + unites[i] + '</option>';
            }
            return opts;
        }

        function buildArticleRow(prefix) {
            // No "required" attributes — validation is JS-only to avoid silent HTML5 blocking
            return '<div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">' +
                '<div class="col-span-12 md:col-span-4">' +
                '<select name="' + prefix + '[article_id]" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-select" onchange="fillArticleData(this)">' +
                buildArticleOptions() + '</select>' +
                '<input type="text" name="' + prefix + '[nouveau_nom]" placeholder="Nom du nouvel article" class="hidden w-full mt-1 px-2 py-2 border border-gray-300 rounded-lg text-sm new-article-name">' +
                '</div>' +
                '<div class="col-span-6 md:col-span-2"><select name="' + prefix + '[unite]" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-unite">' + buildUniteOptions('') + '</select></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[quantite]" placeholder="Qté" step="0.01" min="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm"></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[prix_unitaire_ht]" placeholder="Prix HT" step="0.01" min="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-prix"></div>' +
                '<div class="col-span-5 md:col-span-1"><input type="number" name="' + prefix + '[remise_pourcentage]" placeholder="%" min="0" max="100" value="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm"></div>' +
                '<div class="col-span-1 flex items-center justify-center"><button type="button" onclick="this.closest(\'.grid\').remove()" class="text-red-500 hover:bg-red-100 p-1 rounded-lg"><span class="material-icons">close</span></button></div>' +
                '</div>';
        }

        function addCategory() {
            catCount++;
            var html =
                '<div class="category-item border-2 border-gray-200 rounded-xl p-5 hover:border-green-300 transition">' +
                '<div class="flex items-center justify-between mb-4">' +
                '<div class="flex items-center gap-3 flex-1"><span class="material-icons text-green-600">category</span>' +
                '<div class="flex-1"><label class="block text-xs font-medium text-gray-500 mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>' +
                '<input type="text" name="categories[' + catCount + '][nom]" list="categories-list" placeholder="Nom de la catégorie" class="w-full text-lg font-bold border-b-2 border-transparent hover:border-gray-300 focus:border-green-600 focus:outline-none px-2 py-1">' +
                '</div></div>' +
                '<button type="button" onclick="deleteCategory(this)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg"><span class="material-icons">delete</span></button>' +
                '</div>' +
                '<div class="articles-list space-y-3 mb-4" data-cat="' + catCount + '"></div>' +
                '<button type="button" onclick="addArticle(' + catCount + ')" class="w-full p-3 border-2 border-dashed rounded-lg text-green-600 hover:bg-green-50 mb-4">+ Ajouter un article</button>' +
                '<div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-200">' +
                '<span class="material-icons text-amber-600">build</span>' +
                '<label class="text-sm font-medium flex-1">Main d\'œuvre catégorie</label>' +
                '<input type="number" name="categories[' + catCount + '][main_oeuvre]" placeholder="0" step="1" class="w-32 px-3 py-2 border rounded-lg text-sm">' +
                '<span class="text-sm">FCFA</span></div></div>';
            document.getElementById('categories-container').insertAdjacentHTML('beforeend', html);
        }

        function deleteCategory(btn) {
            if (confirm('Supprimer cette catégorie ?')) btn.closest('.category-item').remove();
        }

        function addArticle(catId) {
            if (!artCounters[catId]) artCounters[catId] = 0;
            var artIdx    = artCounters[catId]++;
            var prefix    = 'categories[' + catId + '][articles][' + artIdx + ']';
            var container = document.querySelector('[data-cat="' + catId + '"]');
            if (container) container.insertAdjacentHTML('beforeend', buildArticleRow(prefix));
        }

        function addArticleSansCat() {
            var artIdx = artSansCatCount++;
            var prefix = 'articles_sans_categorie[' + artIdx + ']';
            document.getElementById('articles-sans-cat').insertAdjacentHTML('beforeend', buildArticleRow(prefix));
        }

        function fillArticleData(select) {
            var row          = select.closest('.grid');
            var option       = select.options[select.selectedIndex];
            var newNameInput = row.querySelector('.new-article-name');
            var uniteSelect  = row.querySelector('.article-unite');
            var prixInput    = row.querySelector('.article-prix');

            if (select.value === 'new') {
                if (newNameInput) { newNameInput.classList.remove('hidden'); }
                if (uniteSelect)  uniteSelect.value = '';
                if (prixInput)    { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            } else if (select.value) {
                if (newNameInput) { newNameInput.classList.add('hidden'); newNameInput.value = ''; }
                if (uniteSelect) {
                    var uniteVal = option.dataset.unite || '';
                    var matched  = false;
                    for (var i = 0; i < uniteSelect.options.length; i++) {
                        if (uniteSelect.options[i].value === uniteVal) { uniteSelect.value = uniteVal; matched = true; break; }
                    }
                    if (!matched) uniteSelect.value = '';
                }
                if (prixInput) {
                    prixInput.value = option.dataset.prix || '';
                    if (option.dataset.modifiable === '0') { prixInput.setAttribute('readonly', true); prixInput.classList.add('bg-gray-100'); }
                    else { prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
                }
            } else {
                if (newNameInput) { newNameInput.classList.add('hidden'); newNameInput.value = ''; }
                if (uniteSelect)  uniteSelect.value = '';
                if (prixInput)    { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            }
        }

        // ── RECAP ──────────────────────────────────────────────────────────
        function buildRecap() {
            var titre  = document.querySelector('[name="titre"]');
            var type   = document.querySelector('[name="type"]');
            var devise = document.querySelector('[name="devise"]');
            document.getElementById('recap-titre').textContent      = titre  ? titre.value  : '—';
            document.getElementById('recap-type').textContent       = type   ? type.options[type.selectedIndex].text : '—';
            document.getElementById('recap-devise').textContent     = devise ? devise.value : 'FCFA';
            document.getElementById('recap-client-nom').textContent = selectedClientNom || '—';

            var totalHT         = 0;
            var totalMainOeuvre = 0;
            var deviseVal       = devise ? devise.value : 'FCFA';

            document.querySelectorAll('[name*="[prix_unitaire_ht]"]').forEach(function(prix) {
                var row = prix.closest('.grid');
                if (!row) return;
                var qte    = row.querySelector('[name*="[quantite]"]');
                var remise = row.querySelector('[name*="[remise_pourcentage]"]');
                if (prix.value && qte && qte.value) {
                    var brut = parseFloat(prix.value) * parseFloat(qte.value);
                    var r    = (remise && remise.value) ? parseFloat(remise.value) : 0;
                    totalHT += brut - (brut * r / 100);
                }
            });

            document.querySelectorAll('[name*="[main_oeuvre]"], [name="main_oeuvre"]').forEach(function(inp) {
                if (inp.value) totalMainOeuvre += parseFloat(inp.value);
            });

            // TPS = 9,5% de la main d'œuvre uniquement
            // CSS = 1% du total HT
            var tauxTps  = TAUX_TPS_BAS;
            var totalTps = totalMainOeuvre * tauxTps / 100;
            var totalCss = totalHT * TAUX_CSS / 100;
            var totalTTC = totalHT + totalMainOeuvre + totalTps + totalCss;

            var fmt = function(n) { return Math.round(n).toLocaleString('fr-FR') + ' ' + deviseVal; };

            document.getElementById('recap-ht').textContent       = fmt(totalHT);
            document.getElementById('recap-taux-tps').textContent = tauxTps.toString().replace('.', ',');
            document.getElementById('recap-tps').textContent      = fmt(totalTps);
            document.getElementById('recap-css').textContent      = fmt(totalCss);
            document.getElementById('recap-mo').textContent       = fmt(totalMainOeuvre);
            document.getElementById('recap-total').textContent    = fmt(totalTTC);
        }

        // ── INIT ───────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            var hiddenVal = document.getElementById('selected_client_id').value;
            if (hiddenVal) selectedClientId = hiddenVal;
            updateStepper();
        });
    </script>
@endsection