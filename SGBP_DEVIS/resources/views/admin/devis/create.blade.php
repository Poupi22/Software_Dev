@extends('admin.layouts.app')
@section('content')
    <style>
        .step-circle { transition: all 0.3s ease; }
        .step-circle.active { background: #2563EB; color: white; }
        .step-circle.completed { background: #10B981; color: white; }
        .step-line { transition: all 0.3s ease; }
        .step-line.completed { background: #10B981; }
        .form-step { display: none; animation: fadeIn 0.4s ease; }
        .form-step.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.devis.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold">Nouveau Devis</h2>
                        <p class="text-xs md:text-sm text-gray-500">4 étapes simples</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stepper -->
        <div class="bg-white border-b px-4 md:px-8 py-6 shadow-sm">
            <div class="max-w-5xl mx-auto flex items-center justify-between">
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-1" class="step-circle active w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center font-bold shadow-lg">1</div>
                    <p class="text-xs md:text-sm font-medium text-blue-600 mt-2">Client</p>
                </div>
                <div id="line-1" class="step-line h-1 flex-1 bg-gray-200 -mt-8"></div>
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-2" class="step-circle w-10 h-10 md:w-12 md:h-12 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">2</div>
                    <p class="text-xs md:text-sm text-gray-500 mt-2">Devis</p>
                </div>
                <div id="line-2" class="step-line h-1 flex-1 bg-gray-200 -mt-8"></div>
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-3" class="step-circle w-10 h-10 md:w-12 md:h-12 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">3</div>
                    <p class="text-xs md:text-sm text-gray-500 mt-2">Articles</p>
                </div>
                <div id="line-3" class="step-line h-1 flex-1 bg-gray-200 -mt-8"></div>
                <div class="flex flex-col items-center flex-1">
                    <div id="circle-4" class="step-circle w-10 h-10 md:w-12 md:h-12 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">4</div>
                    <p class="text-xs md:text-sm text-gray-500 mt-2">Révision</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.devis.store') }}" method="POST" id="devisForm">
            @csrf
            <input type="hidden" name="type" value="provisoire">
            <input type="hidden" name="client_id" id="selected_client_id" value="{{ old('client_id') }}">

            @if ($errors->any())
                <div class="p-4 md:p-8 max-w-5xl mx-auto">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-icons text-red-500">error</span>
                            <strong class="text-red-700">Erreurs de validation :</strong>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="p-4 md:p-8 max-w-5xl mx-auto">

                <!-- STEP 1: Client -->
                <div id="step-1" class="form-step active">
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold">Sélection du client</h3>
                            <a href="{{ route('admin.clients.create', ['redirect' => 'devis.create']) }}"
                                class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <span class="material-icons text-sm">person_add</span>
                                <span class="hidden md:inline">Nouveau client</span>
                            </a>
                        </div>

                        @if (session('client_created'))
                            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                                <p class="text-green-700 flex items-center gap-2">
                                    <span class="material-icons">check_circle</span>
                                    Client créé avec succès ! Sélectionnez-le ci-dessous.
                                </p>
                            </div>
                        @endif

                        <div class="relative mb-6">
                            <span class="material-icons absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                            <input type="text" id="search-client" placeholder="Rechercher un client..."
                                class="w-full pl-12 pr-4 py-3 border rounded-lg" onkeyup="searchClients()">
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto" id="client-list">
                            @forelse($clients as $client)
                                <div class="client-item flex items-center gap-4 p-4 border-2 border-transparent hover:border-blue-600 bg-white hover:bg-blue-50 rounded-lg cursor-pointer transition
                                {{ session('new_client_id') == $client->id ? 'border-blue-600 bg-blue-50' : '' }}"
                                    onclick="selectClient({{ $client->id }}, '{{ addslashes($client->nom_complet) }}')">
                                    <div class="w-14 h-14 {{ $client->type === 'societe' ? 'bg-purple-600' : 'bg-green-600' }} rounded-full text-white font-bold flex items-center justify-center">
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
                                    <span class="material-icons text-gray-300 client-check {{ session('new_client_id') == $client->id ? 'text-blue-600' : '' }}">
                                        {{ session('new_client_id') == $client->id ? 'check_circle' : 'radio_button_unchecked' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">people_outline</span>
                                    <p>Aucun client disponible</p>
                                    <a href="{{ route('admin.clients.create', ['redirect' => 'devis.create']) }}"
                                        class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <span class="material-icons text-sm">add</span>
                                        Créer votre premier client
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Infos Devis -->
                <div id="step-2" class="form-step">
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 space-y-6">
                        <h3 class="text-xl font-bold">Informations du devis</h3>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800">Type : <strong>Provisoire</strong> (par défaut)</p>
                            <p class="text-xs text-blue-600 mt-1">Vous pourrez finaliser après création</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Titre <span class="text-red-500">*</span></label>
                            <input type="text" name="titre" class="w-full px-4 py-3 border rounded-lg"
                                placeholder="Ex: Travaux de rénovation" value="{{ old('titre') }}">
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Devise <span class="text-red-500">*</span></label>
                                <select name="devise" class="w-full px-4 py-3 border rounded-lg">
                                    <option value="FCFA" {{ old('devise', 'FCFA') == 'FCFA' ? 'selected' : '' }}>FCFA (Franc CFA)</option>
                                    <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Validité du devis <span class="text-red-500">*</span></label>
                                <select name="validite_mois" class="w-full px-4 py-3 border rounded-lg">
                                    <option value="1"  {{ old('validite_mois', '1') == '1'  ? 'selected' : '' }}>1 mois</option>
                                    <option value="2"  {{ old('validite_mois') == '2'  ? 'selected' : '' }}>2 mois</option>
                                    <option value="3"  {{ old('validite_mois') == '3'  ? 'selected' : '' }}>3 mois</option>
                                    <option value="6"  {{ old('validite_mois') == '6'  ? 'selected' : '' }}>6 mois</option>
                                    <option value="12" {{ old('validite_mois') == '12' ? 'selected' : '' }}>12 mois</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Durée de validité à partir de la date d'émission</p>
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
                            <label class="block text-sm font-medium mb-2">Introduction</label>
                            <textarea name="introduction" rows="4" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('introduction') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Conclusion</label>
                            <textarea name="conclusion" rows="4" class="w-full px-4 py-3 border rounded-lg resize-none">{{ old('conclusion') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Articles -->
                <div id="step-3" class="form-step">
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                        <h3 class="text-xl font-bold mb-6">Articles et catégories</h3>

                        <button type="button" onclick="addCategory()"
                            class="w-full p-5 border-2 border-dashed border-blue-300 rounded-xl text-blue-600 hover:bg-blue-50 font-medium mb-6">
                            + Ajouter une catégorie
                        </button>

                        <div id="categories-container" class="space-y-6 mb-6"></div>

                        <!-- Articles SANS catégorie -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="font-bold mb-4">Articles sans catégorie</h4>
                            <div id="articles-sans-cat" class="space-y-3"></div>
                            <button type="button" onclick="addArticleSansCat()"
                                class="w-full p-3 border-2 border-dashed rounded-lg text-blue-600 hover:bg-blue-50 mt-3">
                                + Ajouter un article
                            </button>
                            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 mt-4">
                                <div class="flex items-center gap-4">
                                    <span class="material-icons text-amber-600">build</span>
                                    <label class="text-sm font-medium text-gray-700 flex-1">Main d'œuvre hors catégorie (HT)</label>
                                    <select onchange="toggleMainOeuvreMode(this, 'mo-global')" class="px-2 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="montant">Montant fixe</option>
                                        <option value="pourcentage">Pourcentage</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 mt-2" id="mo-global-montant">
                                    <input type="number" name="main_oeuvre" placeholder="0" step="0.01" min="0" value="0"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">FCFA</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2 hidden" id="mo-global-pourcentage">
                                    <input type="number" name="main_oeuvre_pourcentage" placeholder="0" step="0.01" min="0" max="100"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">% du total HT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Révision -->
                <div id="step-4" class="form-step">
                    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                        <h3 class="text-xl font-bold mb-6">Révision finale</h3>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="p-5 bg-blue-50 rounded-lg">
                                <h4 class="font-bold mb-3">Informations</h4>
                                <div class="space-y-2 text-sm">
                                    <p><span class="text-gray-600">Titre :</span> <strong id="recap-titre">-</strong></p>
                                    <p><span class="text-gray-600">Devise :</span> <strong id="recap-devise">-</strong></p>
                                    <p><span class="text-gray-600">Validité :</span> <strong id="recap-validite">-</strong></p>
                                </div>
                            </div>
                            <div class="p-5 bg-green-50 rounded-lg">
                                <h4 class="font-bold mb-3">Client</h4>
                                <p class="font-medium" id="recap-client-nom">-</p>
                            </div>
                        </div>

                        <!-- Détail taxes -->
                        <div class="mt-6 p-5 bg-gray-50 rounded-xl border border-gray-200">
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

                        <!-- Total -->
                        <div class="mt-4 p-6 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl text-white">
                            <p class="text-sm mb-1">Montant total TTC</p>
                            <p class="text-4xl font-bold" id="recap-total">0 FCFA</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="btn-prev" onclick="previousStep()"
                        class="px-6 py-3 border-2 rounded-lg hover:bg-gray-100" style="display:none;">
                        ← Précédent
                    </button>
                    <button type="button" id="btn-next" onclick="nextStep()"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-auto">
                        Suivant →
                    </button>
                    <button type="button" id="btn-submit" onclick="submitForm()"
                        class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 ml-auto">
                        <span class="material-icons text-sm">check_circle</span> Créer le devis
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
        // ── VARIABLES GLOBALES ──────────────────────────────────────────────
        var currentStep       = 1;
        var selectedClientId  = {{ old('client_id') ? old('client_id') : 'null' }};
        var selectedClientNom = '';
        var catCount          = 0;
        var artCounters       = {};
        var artSansCatCount   = 0;

        // Constantes TPS/CSS Gabon
        var SEUIL_TPS_HAUT = 60000000;
        var TAUX_TPS_BAS   = 9.5;
        var TAUX_TPS_HAUT  = 18.0;
        var TAUX_CSS       = 1.0;

        // Articles data
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

        var categoriesExistantes = [];
        @if (isset($categoriesExistantes) && $categoriesExistantes->count() > 0)
            categoriesExistantes = [
                @foreach ($categoriesExistantes as $cat)
                    "{{ addslashes($cat->nom) }}"{{ $loop->last ? '' : ',' }}
                @endforeach
            ];
        @endif

        // ── NAVIGATION STEPS ───────────────────────────────────────────────
        function nextStep() {
            if (!validateStep(currentStep)) return;
            if (currentStep < 4) {
                document.getElementById('step-' + currentStep).classList.remove('active');
                currentStep++;
                document.getElementById('step-' + currentStep).classList.add('active');
                if (currentStep === 4) buildRecap();
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

        // ── SOUMISSION MANUELLE (évite la validation HTML5 native) ─────────
        function submitForm() {
            // Retirer temporairement tous les attributs disabled pour s'assurer
            // que les champs cachés ne bloquent pas la soumission native
            document.getElementById('devisForm').submit();
        }

        // ── VALIDATION PAR STEP AVEC MESSAGES INLINE ──────────────────────
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
            }

            if (step === 2) {
                var titre = document.querySelector('[name="titre"]');
                if (!titre || !titre.value.trim()) {
                    highlightField(titre, true);
                    showStepError(2, '⚠️ Le titre du devis est obligatoire.');
                    if (titre) titre.focus();
                    return false;
                }
                highlightField(titre, false);
            }

            if (step === 3) {
                var hasCategories   = document.querySelectorAll('#categories-container .category-item').length > 0;
                var hasArticlesSans = document.querySelectorAll('#articles-sans-cat > div.grid').length > 0;

                if (!hasCategories && !hasArticlesSans) {
                    showStepError(3, '⚠️ Veuillez ajouter au moins un article ou une catégorie au devis.');
                    return false;
                }

                // ── NOUVEAU : vérifier les noms de catégories ──
                var catNomInputs = document.querySelectorAll('#categories-container [name*="[nom]"]');
                var missingCatNom = false;
                catNomInputs.forEach(function(f) {
                    if (!f.value.trim()) {
                        highlightField(f, true);
                        missingCatNom = true;
                    } else {
                        highlightField(f, false);
                    }
                });
                if (missingCatNom) {
                    showStepError(3, '⚠️ Veuillez renseigner le nom de chaque catégorie.');
                    return false;
                }

                // ── Vérifier les sélects d'articles ──
                var articleSelects = document.querySelectorAll('#categories-container .article-select, #articles-sans-cat .article-select');
                var missingArticle = false;
                articleSelects.forEach(function(sel) {
                    if (!sel.value) {
                        highlightField(sel, true);
                        missingArticle = true;
                    } else {
                        highlightField(sel, false);
                    }
                });

                // ── Vérifier le nom du nouvel article si "new" sélectionné ──
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

                // ── Vérifier quantité, prix ──
                var missingFields = false;
                document.querySelectorAll('#categories-container [name*="[quantite]"], #articles-sans-cat [name*="[quantite]"]').forEach(function(f) {
                    if (!f.value) { highlightField(f, true); missingFields = true; }
                    else highlightField(f, false);
                });
                document.querySelectorAll('#categories-container .article-prix, #articles-sans-cat .article-prix').forEach(function(f) {
                    if (!f.value) { highlightField(f, true); missingFields = true; }
                    else highlightField(f, false);
                });

                if (missingArticle) {
                    showStepError(3, '⚠️ Veuillez sélectionner un article pour chaque ligne.');
                    return false;
                }
                if (missingNewName) {
                    showStepError(3, '⚠️ Veuillez saisir le nom du nouvel article.');
                    return false;
                }
                if (missingFields) {
                    showStepError(3, '⚠️ Veuillez remplir tous les champs obligatoires des articles (quantité, prix HT).');
                    return false;
                }
            }

            return true;
        }

        function updateStepper() {
            for (var i = 1; i <= 4; i++) {
                var circle = document.getElementById('circle-' + i);
                var line   = document.getElementById('line-' + i);
                if (i < currentStep) {
                    circle.classList.add('completed'); circle.classList.remove('active');
                    circle.innerHTML = '<span class="material-icons text-sm">check</span>';
                    if (line) line.classList.add('completed');
                } else if (i === currentStep) {
                    circle.classList.add('active'); circle.classList.remove('completed');
                    circle.textContent = i;
                } else {
                    circle.classList.remove('active', 'completed');
                    circle.textContent = i;
                    if (line) line.classList.remove('completed');
                }
            }
            var btnPrev   = document.getElementById('btn-prev');
            var btnNext   = document.getElementById('btn-next');
            var btnSubmit = document.getElementById('btn-submit');
            if (btnPrev) btnPrev.style.display = currentStep === 1 ? 'none' : 'flex';
            if (currentStep === 4) {
                if (btnNext)   btnNext.classList.add('hidden');
                if (btnSubmit) btnSubmit.classList.remove('hidden');
            } else {
                if (btnNext)   { btnNext.classList.remove('hidden'); btnNext.innerHTML = 'Suivant <span class="material-icons">arrow_forward</span>'; }
                if (btnSubmit) btnSubmit.classList.add('hidden');
            }
        }

        // ── GESTION CLIENT ─────────────────────────────────────────────────
        function selectClient(id, nom) {
            selectedClientId  = id;
            selectedClientNom = nom;
            document.getElementById('selected_client_id').value = id;
            document.querySelectorAll('.client-item').forEach(function(item) {
                item.classList.remove('border-blue-600', 'bg-blue-50');
                var check = item.querySelector('.client-check');
                if (check) { check.textContent = 'radio_button_unchecked'; check.classList.remove('text-blue-600'); check.classList.add('text-gray-300'); }
            });
            event.currentTarget.classList.add('border-blue-600', 'bg-blue-50');
            var currentCheck = event.currentTarget.querySelector('.client-check');
            if (currentCheck) { currentCheck.textContent = 'check_circle'; currentCheck.classList.add('text-blue-600'); currentCheck.classList.remove('text-gray-300'); }
        }

        function searchClients() {
            var val = document.getElementById('search-client').value.toLowerCase();
            document.querySelectorAll('.client-item').forEach(function(item) {
                item.style.display = item.textContent.toLowerCase().includes(val) ? 'flex' : 'none';
            });
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
            updateRecap();
        }

        // ── GESTION CATÉGORIES ─────────────────────────────────────────────
        function addCategory() {
            catCount++;
            var html =
                '<div class="category-item border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">' +
                '<div class="flex items-center justify-between mb-4">' +
                '<div class="flex items-center gap-3 flex-1">' +
                '<span class="material-icons text-blue-600">category</span>' +
                '<div class="flex-1">' +
                '<label class="block text-xs font-medium text-gray-500 mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>' +
                '<input type="text" name="categories[' + catCount + '][nom]" list="categories-list" placeholder="Choisir ou taper une catégorie" class="w-full text-lg font-bold border-b-2 border-transparent hover:border-gray-300 focus:border-blue-600 focus:outline-none px-2 py-1">' +
                '</div></div>' +
                '<button type="button" onclick="deleteCategory(this)" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition"><span class="material-icons">delete</span></button>' +
                '</div>' +
                '<div class="articles-list space-y-3 mb-4" data-cat="' + catCount + '"></div>' +
                '<button type="button" onclick="addArticle(' + catCount + ')" class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 font-medium flex items-center justify-center gap-2 transition mb-4">' +
                '<span class="material-icons">add</span> Ajouter un article</button>' +
                '<div class="p-3 bg-amber-50 rounded-lg border border-amber-200">' +
                '<div class="flex items-center gap-4">' +
                '<span class="material-icons text-amber-600">build</span>' +
                '<label class="text-sm font-medium text-gray-700 flex-1">Main d\'œuvre (HT)</label>' +
                '<select onchange="toggleMainOeuvreMode(this, \'mo-cat-' + catCount + '\')" class="px-2 py-2 border border-gray-300 rounded-lg text-sm">' +
                '<option value="montant">Montant fixe</option>' +
                '<option value="pourcentage">Pourcentage</option>' +
                '</select></div>' +
                '<div class="flex items-center gap-2 mt-2" id="mo-cat-' + catCount + '-montant">' +
                '<input type="number" name="categories[' + catCount + '][main_oeuvre]" placeholder="0" step="0.01" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">' +
                '<span class="text-sm font-medium text-gray-700">FCFA</span></div>' +
                '<div class="flex items-center gap-2 mt-2 hidden" id="mo-cat-' + catCount + '-pourcentage">' +
                '<input type="number" name="categories[' + catCount + '][main_oeuvre_pourcentage]" placeholder="0" step="0.01" min="0" max="100" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">' +
                '<span class="text-sm font-medium text-gray-700">% du sous-total</span></div>' +
                '</div></div>';
            document.getElementById('categories-container').insertAdjacentHTML('beforeend', html);
        }

        function deleteCategory(btn) {
            if (confirm('Supprimer cette catégorie et tous ses articles ?')) btn.closest('.category-item').remove();
        }

        // ── GESTION ARTICLES ───────────────────────────────────────────────
        function buildArticleOptions() {
            var opts = '<option value="">-- Choisir un article --</option><option value="new">➕ Créer un nouvel article</option>';
            for (var i = 0; i < articlesData.length; i++) {
                var a = articlesData[i];
                opts += '<option value="' + a.id + '" data-prix="' + a.prix_ht + '" data-unite="' + a.unite + '" data-modifiable="' + a.prix_modifiable + '">' + a.nom + '</option>';
            }
            return opts;
        }

        function buildUniteOptions(selectedValue) {
            var unites = ['m³', 'm²', 'ml', 'pf'];
            var opts = '<option value="">-- Unité --</option>';
            for (var i = 0; i < unites.length; i++) {
                var sel = (selectedValue && selectedValue === unites[i]) ? ' selected' : '';
                opts += '<option value="' + unites[i] + '"' + sel + '>' + unites[i] + '</option>';
            }
            return opts;
        }

        function buildArticleRow(prefix) {
            // Aucun attribut "required" sur les champs dynamiques — validation JS uniquement
            return '<div class="grid grid-cols-12 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">' +
                '<div class="col-span-12 md:col-span-4">' +
                '<select name="' + prefix + '[article_id]" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-select" onchange="fillArticleData(this)">' +
                buildArticleOptions() + '</select>' +
                '<input type="text" name="' + prefix + '[nouveau_nom]" placeholder="Nom du nouvel article" class="hidden w-full px-2 py-2 border border-gray-300 rounded-lg text-sm mt-1 new-article-name">' +
                '</div>' +
                '<div class="col-span-6 md:col-span-2"><select name="' + prefix + '[unite]" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-unite">' + buildUniteOptions('') + '</select></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[quantite]" placeholder="Qté" step="0.01" min="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm"></div>' +
                '<div class="col-span-6 md:col-span-2"><input type="number" name="' + prefix + '[prix_unitaire_ht]" placeholder="Prix HT" step="0.01" min="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm article-prix"></div>' +
                '<div class="col-span-5 md:col-span-1"><input type="number" name="' + prefix + '[remise_pourcentage]" placeholder="%" min="0" max="100" value="0" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm"></div>' +
                '<div class="col-span-1 flex items-center justify-center"><button type="button" onclick="this.closest(\'.grid\').remove()" class="text-red-600 hover:bg-red-100 p-2 rounded-lg transition"><span class="material-icons text-lg">close</span></button></div>' +
                '</div>';
        }

        function addArticle(catId) {
            if (!artCounters[catId]) artCounters[catId] = 0;
            var artIdx  = artCounters[catId]++;
            var prefix  = 'categories[' + catId + '][articles][' + artIdx + ']';
            var container = document.querySelector('[data-cat="' + catId + '"]');
            if (container) container.insertAdjacentHTML('beforeend', buildArticleRow(prefix));
        }

        function addArticleSansCat() {
            var artIdx = artSansCatCount++;
            var prefix = 'articles_sans_categorie[' + artIdx + ']';
            document.getElementById('articles-sans-cat').insertAdjacentHTML('beforeend', buildArticleRow(prefix));
        }

        function fillArticleData(select) {
            var row = select.closest('.grid');
            if (!row) return;
            var option       = select.options[select.selectedIndex];
            var newNameInput = row.querySelector('.new-article-name');
            var uniteSelect  = row.querySelector('.article-unite');
            var prixInput    = row.querySelector('.article-prix');

            if (select.value === 'new') {
                // Afficher le champ nom — PAS de "required" pour éviter le blocage HTML5
                if (newNameInput) { newNameInput.classList.remove('hidden'); }
                if (uniteSelect) uniteSelect.value = '';
                if (prixInput)  { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            } else if (select.value) {
                if (newNameInput) { newNameInput.classList.add('hidden'); newNameInput.value = ''; }
                if (uniteSelect) {
                    var uniteVal = option.dataset.unite || '';
                    var matched = false;
                    for (var i = 0; i < uniteSelect.options.length; i++) {
                        if (uniteSelect.options[i].value === uniteVal) {
                            uniteSelect.value = uniteVal;
                            matched = true;
                            break;
                        }
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
                if (uniteSelect) uniteSelect.value = '';
                if (prixInput)  { prixInput.value = ''; prixInput.removeAttribute('readonly'); prixInput.classList.remove('bg-gray-100'); }
            }
        }

        // ── STEP 4 : RÉVISION ──────────────────────────────────────────────
        function buildRecap() {
            var titre    = document.querySelector('[name="titre"]');
            var devise   = document.querySelector('[name="devise"]');
            var validite = document.querySelector('[name="validite_mois"]');

            var recapTitre     = document.getElementById('recap-titre');
            var recapDevise    = document.getElementById('recap-devise');
            var recapValidite  = document.getElementById('recap-validite');
            var recapClientNom = document.getElementById('recap-client-nom');

            if (recapTitre)     recapTitre.textContent     = titre    ? titre.value    : '-';
            if (recapDevise)    recapDevise.textContent    = devise   ? devise.value   : 'FCFA';
            if (recapValidite)  recapValidite.textContent  = validite ? validite.value + ' mois' : '-';
            if (recapClientNom) recapClientNom.textContent = selectedClientNom || '-';

            var totalHT         = 0;
            var totalMainOeuvre = 0;
            var deviseText      = devise ? devise.value : 'FCFA';

            var prixInputs     = document.querySelectorAll('#categories-container [name*="[prix_unitaire_ht]"]');
            var quantiteInputs = document.querySelectorAll('#categories-container [name*="[quantite]"]');
            var remiseInputs   = document.querySelectorAll('#categories-container [name*="[remise_pourcentage]"]');
            for (var i = 0; i < prixInputs.length; i++) {
                if (prixInputs[i].value && quantiteInputs[i] && quantiteInputs[i].value) {
                    var brut   = parseFloat(prixInputs[i].value) * parseFloat(quantiteInputs[i].value);
                    var remise = (remiseInputs[i] && remiseInputs[i].value) ? parseFloat(remiseInputs[i].value) : 0;
                    totalHT   += brut - (brut * remise / 100);
                }
            }

            var prixSansCat   = document.querySelectorAll('#articles-sans-cat [name*="[prix_unitaire_ht]"]');
            var qteSansCat    = document.querySelectorAll('#articles-sans-cat [name*="[quantite]"]');
            var remiseSansCat = document.querySelectorAll('#articles-sans-cat [name*="[remise_pourcentage]"]');
            for (var i = 0; i < prixSansCat.length; i++) {
                if (prixSansCat[i].value && qteSansCat[i] && qteSansCat[i].value) {
                    var brut   = parseFloat(prixSansCat[i].value) * parseFloat(qteSansCat[i].value);
                    var remise = (remiseSansCat[i] && remiseSansCat[i].value) ? parseFloat(remiseSansCat[i].value) : 0;
                    totalHT   += brut - (brut * remise / 100);
                }
            }

            // Main d'œuvre globale : montant fixe ou pourcentage
            var moGlobalMontant = document.querySelector('[name="main_oeuvre"]');
            var moGlobalPourcentage = document.querySelector('[name="main_oeuvre_pourcentage"]');
            if (moGlobalPourcentage && moGlobalPourcentage.value && !moGlobalPourcentage.closest('.hidden')) {
                totalMainOeuvre += totalHT * parseFloat(moGlobalPourcentage.value) / 100;
            } else if (moGlobalMontant && moGlobalMontant.value) {
                totalMainOeuvre += parseFloat(moGlobalMontant.value);
            }

            // Main d'œuvre par catégorie : montant fixe ou pourcentage
            var catMoMontants = document.querySelectorAll('[name*="[main_oeuvre]"]:not([name="main_oeuvre"]):not([name*="pourcentage"])');
            var catMoPourcentages = document.querySelectorAll('[name*="[main_oeuvre_pourcentage]"]');
            catMoMontants.forEach(function(inp) {
                if (inp.value && !inp.closest('.hidden')) totalMainOeuvre += parseFloat(inp.value);
            });
            catMoPourcentages.forEach(function(inp) {
                if (inp.value && !inp.closest('.hidden')) totalMainOeuvre += totalHT * parseFloat(inp.value) / 100;
            });

            // TPS = 9,5% de la main d'œuvre uniquement
            // CSS = 1% du total HT
            var tauxTps  = TAUX_TPS_BAS;
            var totalTps = totalMainOeuvre * tauxTps / 100;
            var totalCss = totalHT * TAUX_CSS / 100;
            var totalTTC = totalHT + totalMainOeuvre + totalTps + totalCss;

            var fmt = function(n) { return Math.round(n).toLocaleString('fr-FR') + ' ' + deviseText; };

            // Variables séparées pour éviter la redéclaration
            var elHt      = document.getElementById('recap-ht');      if (elHt)      elHt.textContent      = fmt(totalHT);
            var elTauxTps = document.getElementById('recap-taux-tps'); if (elTauxTps) elTauxTps.textContent = tauxTps.toString().replace('.', ',');
            var elTps     = document.getElementById('recap-tps');     if (elTps)     elTps.textContent     = fmt(totalTps);
            var elCss     = document.getElementById('recap-css');     if (elCss)     elCss.textContent     = fmt(totalCss);
            var elMo      = document.getElementById('recap-mo');      if (elMo)      elMo.textContent      = fmt(totalMainOeuvre);
            var elTotal   = document.getElementById('recap-total');   if (elTotal)   elTotal.textContent   = fmt(totalTTC);
        }

        // ── INITIALISATION ─────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            // Synchroniser selectedClientId depuis l'input hidden au cas où old() l'a prérempli
            var hiddenVal = document.getElementById('selected_client_id').value;
            if (hiddenVal) selectedClientId = hiddenVal;

            @if (session('new_client_id'))
                var newClientId = {{ session('new_client_id') }};
                document.querySelectorAll('.client-item').forEach(function(item) {
                    var onclick = item.getAttribute('onclick');
                    if (onclick && onclick.includes('selectClient(' + newClientId)) {
                        item.click();
                        setTimeout(function() { item.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 100);
                    }
                });
            @endif

            @if ($errors->any() && old('client_id'))
                var oldClientId = {{ old('client_id') }};
                document.querySelectorAll('.client-item').forEach(function(item) {
                    var onclick = item.getAttribute('onclick');
                    if (onclick && onclick.includes('selectClient(' + oldClientId)) item.click();
                });
            @endif

            updateStepper();
        });
    </script>
@endsection