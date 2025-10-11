@extends('admin.layouts.app')
@section('title', 'Paramètres')
@section('content')

    <style>
        .tab-button.active {
            background: #EFF6FF;
            color: #2563EB;
            border-left: 3px solid #2563EB;
        }
    </style>

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="history.back()" class="md:hidden text-gray-600">
                        <span class="material-icons">arrow_back</span>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Paramètres</h2>
                        <p class="text-xs md:text-sm text-gray-500">Configurez votre entreprise et le système</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages flash --}}
        @if (session('success'))
            <div class="mx-4 md:mx-8 mt-4">
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <span class="material-icons text-green-600">check_circle</span>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-4 md:mx-8 mt-4">
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-icons text-red-600">error</span>
                        <p class="text-sm font-medium text-red-800">Des erreurs ont été détectées :</p>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Sidebar Tabs -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-2 space-y-1">
                            <button onclick="showTab('entreprise')"
                                class="tab-button active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all">
                                <span class="material-icons text-xl">business</span>
                                <span class="font-medium">Entreprise</span>
                            </button>
                            <button onclick="showTab('signature')"
                                class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">draw</span>
                                <span class="font-medium">Signature & Cachet</span>
                            </button>
                            <button onclick="showTab('notifications')"
                                class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">notifications</span>
                                <span class="font-medium">Notifications</span>
                            </button>
                            <button onclick="showTab('apropos')"
                                class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">language</span>
                                <span class="font-medium">À propos / Site</span>
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="lg:col-span-3">

                        {{-- ═══════════════════════════════════════════════ --}}
                        {{-- TAB 1 : ENTREPRISE --}}
                        {{-- ═══════════════════════════════════════════════ --}}
                        <div id="tab-entreprise" class="tab-content">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Informations de l'entreprise</h3>

                                <form action="{{ route('admin.parametres.update') }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <!-- Logo -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Logo de l'entreprise</label>
                                        <div class="flex items-center gap-6">
                                            <div class="w-24 h-24 bg-blue-50 rounded-xl flex items-center justify-center overflow-hidden border border-gray-200">
                                                @if ($parametre->logo_path)
                                                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                                                @else
                                                    <span class="material-icons text-blue-400 text-4xl">business</span>
                                                @endif
                                            </div>
                                            <div>
                                                <label class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium mb-2 cursor-pointer inline-block">
                                                    Changer le logo
                                                    <input type="file" name="logo" accept="image/png,image/jpeg" class="hidden" onchange="previewFile(this, 'logo-preview')">
                                                </label>
                                                @if ($parametre->logo_path)
                                                    <label class="ml-2 text-sm text-red-600 cursor-pointer hover:underline">
                                                        <input type="checkbox" name="supprimer_logo" value="1" class="hidden"> Supprimer
                                                    </label>
                                                @endif
                                                <p class="text-sm text-gray-500 mt-1">Format PNG ou JPG, max 2 Mo</p>
                                                <p id="logo-preview" class="text-sm text-green-600 mt-1 hidden"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Infos de base -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                                            <input type="text" name="nom_entreprise"
                                                value="{{ old('nom_entreprise', $parametre->nom_entreprise) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="Ex: SGBP SARL">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Forme juridique</label>
                                            <select name="forme_juridique"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                                <option value="">-- Choisir --</option>
                                                @foreach (['SARL', 'SA', 'SAS', 'SASU', 'SCI', 'EI', 'EURL', 'GIE', 'Autre'] as $forme)
                                                    <option value="{{ $forme }}"
                                                        {{ old('forme_juridique', $parametre->forme_juridique) == $forme ? 'selected' : '' }}>
                                                        {{ $forme }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Slogan</label>
                                        <input type="text" name="slogan" value="{{ old('slogan', $parametre->slogan) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="Ex: Votre partenaire BTP de confiance">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">RCCM</label>
                                            <input type="text" name="rccm" value="{{ old('rccm', $parametre->rccm) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="Ex: GA-LBV-01-2020-B12-00XXX">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">NIU / NIF</label>
                                            <input type="text" name="niu"
                                                value="{{ old('niu', $parametre->niu) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="Ex: 000123456A">
                                        </div>
                                    </div>

                                    <!-- Contact -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Email principal</label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $parametre->email) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="contact@entreprise.ga">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone portable</label>
                                            <input type="tel" name="telephone"
                                                value="{{ old('telephone', $parametre->telephone) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="+241 XX XX XX XX">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone fixe</label>
                                            <input type="tel" name="telephone_secondaire"
                                                value="{{ old('telephone_secondaire', $parametre->telephone_secondaire) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="+241 XX XX XX XX">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                                            <input type="url" name="site_web"
                                                value="{{ old('site_web', $parametre->site_web) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="https://www.entreprise.ga">
                                        </div>
                                    </div>

                                    <!-- Adresse -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                        <input type="text" name="adresse"
                                            value="{{ old('adresse', $parametre->adresse) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="Quartier, rue...">
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Boîte postale</label>
                                            <input type="text" name="boite_postale"
                                                value="{{ old('boite_postale', $parametre->boite_postale) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="BP 1234">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                            <input type="text" name="ville"
                                                value="{{ old('ville', $parametre->ville) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="Libreville">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                                            <input type="text" name="pays"
                                                value="{{ old('pays', $parametre->pays) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="Gabon">
                                        </div>
                                    </div>

                                    <!-- Banque -->
                                    <div class="pt-6 border-t border-gray-200">
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-green-600">account_balance</span>
                                            Informations bancaires
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la banque</label>
                                                <input type="text" name="banque_nom"
                                                    value="{{ old('banque_nom', $parametre->banque_nom) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: BGFIBank Gabon">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Titulaire du compte</label>
                                                <input type="text" name="banque_titulaire"
                                                    value="{{ old('banque_titulaire', $parametre->banque_titulaire) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Nom de l'entreprise">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">IBAN / N° de compte</label>
                                                <input type="text" name="banque_iban"
                                                    value="{{ old('banque_iban', $parametre->banque_iban) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="GA21 XXXX XXXX XXXX">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Code SWIFT / BIC</label>
                                                <input type="text" name="banque_swift"
                                                    value="{{ old('banque_swift', $parametre->banque_swift) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="BGFIGAXXXX">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Textes légaux -->
                                    <div class="pt-6 border-t border-gray-200">
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-orange-600">gavel</span>
                                            Textes légaux
                                        </h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Conditions générales</label>
                                                <textarea name="conditions_generales" rows="4"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Conditions générales de vente...">{{ old('conditions_generales', $parametre->conditions_generales) }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Mentions légales</label>
                                                <textarea name="mentions_legales" rows="4"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Mentions légales...">{{ old('mentions_legales', $parametre->mentions_legales) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bouton sauvegarder -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="submit"
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer les modifications</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════ --}}
                        {{-- TAB 2 : SIGNATURE & CACHET --}}
                        {{-- ═══════════════════════════════════════════════ --}}
                        <div id="tab-signature" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Signature et cachet numériques</h3>

                                <form action="{{ route('admin.parametres.update') }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-8">
                                    @csrf
                                    @method('PUT')

                                    <!-- Signature -->
                                    <div>
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-blue-600">draw</span>
                                            <span>Signature numérique</span>
                                        </h4>
                                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                                            <div class="w-full h-32 bg-gray-50 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                                                @if ($parametre->signature_path)
                                                    <img src="{{ asset('storage/' . $parametre->signature_path) }}" alt="Signature" class="max-h-full object-contain">
                                                @else
                                                    <span class="text-gray-400">Aucune signature uploadée</span>
                                                @endif
                                            </div>
                                            <label class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium cursor-pointer inline-block">
                                                {{ $parametre->signature_path ? 'Changer la signature' : 'Télécharger une signature' }}
                                                <input type="file" name="signature" accept="image/png,image/jpeg" class="hidden" onchange="previewFile(this, 'signature-preview')">
                                            </label>
                                            @if ($parametre->signature_path)
                                                <label class="ml-3 px-4 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium cursor-pointer inline-block">
                                                    <input type="checkbox" name="supprimer_signature" value="1" class="hidden peer">
                                                    <span class="peer-checked:font-bold">Supprimer</span>
                                                </label>
                                            @endif
                                            <p class="text-sm text-gray-500 mt-2">Format PNG transparent recommandé, max 2 Mo</p>
                                            <p id="signature-preview" class="text-sm text-green-600 mt-1 hidden"></p>
                                        </div>
                                    </div>

                                    <!-- Cachet -->
                                    <div>
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-purple-600">verified</span>
                                            <span>Cachet de l'entreprise</span>
                                        </h4>
                                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                                            <div class="w-full h-32 bg-gray-50 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                                                @if ($parametre->cachet_path)
                                                    <img src="{{ asset('storage/' . $parametre->cachet_path) }}" alt="Cachet" class="max-h-full object-contain">
                                                @else
                                                    <span class="text-gray-400">Aucun cachet uploadé</span>
                                                @endif
                                            </div>
                                            <label class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium cursor-pointer inline-block">
                                                {{ $parametre->cachet_path ? 'Changer le cachet' : 'Télécharger un cachet' }}
                                                <input type="file" name="cachet" accept="image/png,image/jpeg" class="hidden" onchange="previewFile(this, 'cachet-preview')">
                                            </label>
                                            @if ($parametre->cachet_path)
                                                <label class="ml-3 px-4 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium cursor-pointer inline-block">
                                                    <input type="checkbox" name="supprimer_cachet" value="1" class="hidden peer">
                                                    <span class="peer-checked:font-bold">Supprimer</span>
                                                </label>
                                            @endif
                                            <p class="text-sm text-gray-500 mt-2">Format PNG transparent recommandé, max 2 Mo</p>
                                            <p id="cachet-preview" class="text-sm text-green-600 mt-1 hidden"></p>
                                        </div>
                                    </div>

                                    <!-- Signataire -->
                                    <div>
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-gray-600">person</span>
                                            Informations du signataire
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom du signataire</label>
                                                <input type="text" name="signataire_nom"
                                                    value="{{ old('signataire_nom', $parametre->signataire_nom) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: Jean-Pierre Moussavou">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Fonction</label>
                                                <input type="text" name="signataire_fonction"
                                                    value="{{ old('signataire_fonction', $parametre->signataire_fonction) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: Directeur Général">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bouton sauvegarder -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="submit"
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════ --}}
                        {{-- TAB 3 : NOTIFICATIONS --}}
                        {{-- ═══════════════════════════════════════════════ --}}
                        <div id="tab-notifications" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Paramètres de notifications</h3>

                                <form action="{{ route('admin.parametres.update') }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <div class="p-4 bg-blue-50 rounded-lg flex items-start gap-3">
                                        <span class="material-icons text-blue-600">info</span>
                                        <p class="text-sm text-blue-800">
                                            Configurez les alertes et relances automatiques pour ne jamais manquer une échéance importante.
                                        </p>
                                    </div>

                                    <!-- Email expéditeur documents -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email expéditeur des documents</label>
                                        <input type="email" name="email_expediteur"
                                            value="{{ old('email_expediteur', $parametre->email_expediteur) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="envoi@entreprise.ga">
                                        <p class="text-xs text-gray-500 mt-1">Email utilisé comme expéditeur lors de l'envoi des factures, devis et PV.</p>
                                    </div>

                                    <!-- Email notifications -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email de réception des notifications</label>
                                        <input type="email" name="email_notifications"
                                            value="{{ old('email_notifications', $parametre->email_notifications) }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="notifications@entreprise.ga">
                                    </div>

                                    <!-- Toggles notifications -->
                                    <div class="space-y-3">
                                        <h4 class="font-bold text-gray-800">Types de notifications</h4>

                                        @php
                                            $toggles = [
                                                'notif_nouveau_devis' => [
                                                    'Nouveau devis',
                                                    'Recevoir une notification lors de la création d\'un devis',
                                                ],
                                                'notif_devis_accepte' => [
                                                    'Devis accepté',
                                                    'Recevoir une notification quand un devis est accepté',
                                                ],
                                                'notif_nouvelle_facture' => [
                                                    'Nouvelle facture',
                                                    'Recevoir une notification lors de la création d\'une facture',
                                                ],
                                                'notif_paiement_recu' => [
                                                    'Paiement reçu',
                                                    'Recevoir une notification lors d\'un paiement',
                                                ],
                                                'notif_nouveau_prospect' => [
                                                    'Nouveau prospect',
                                                    'Recevoir une notification lors de l\'ajout d\'un prospect',
                                                ],
                                            ];
                                        @endphp

                                        @foreach ($toggles as $field => [$label, $desc])
                                            <div class="p-4 border border-gray-200 rounded-xl flex items-center justify-between">
                                                <div>
                                                    <h5 class="font-medium text-gray-800">{{ $label }}</h5>
                                                    <p class="text-sm text-gray-500">{{ $desc }}</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="hidden" name="{{ $field }}" value="0">
                                                    <input type="checkbox" name="{{ $field }}" value="1"
                                                        {{ old($field, $parametre->$field) ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Relances automatiques -->
                                    <div class="pt-6 border-t border-gray-200">
                                        <div class="p-4 border border-gray-200 rounded-xl">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h4 class="font-bold text-gray-800">Relances automatiques</h4>
                                                    <p class="text-sm text-gray-500">Activer l'envoi automatique de relances par email</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="hidden" name="relance_auto_active" value="0">
                                                    <input type="checkbox" name="relance_auto_active" value="1"
                                                        {{ old('relance_auto_active', $parametre->relance_auto_active) ? 'checked' : '' }}
                                                        class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-2">Délai relance devis (jours)</label>
                                                    <input type="number" name="delai_relance_devis"
                                                        value="{{ old('delai_relance_devis', $parametre->delai_relance_devis) }}"
                                                        min="1"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                        placeholder="7">
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-2">Délai relance facture (jours)</label>
                                                    <input type="number" name="delai_relance_facture"
                                                        value="{{ old('delai_relance_facture', $parametre->delai_relance_facture) }}"
                                                        min="1"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                        placeholder="15">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bouton sauvegarder -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="submit"
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════ --}}
                        {{-- TAB 4 : À PROPOS / SITE --}}
                        {{-- ═══════════════════════════════════════════════ --}}
                        <div id="tab-apropos" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">À propos & Site vitrine</h3>

                                <form action="{{ route('admin.parametres.update') }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <!-- Image À propos -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Image « À propos »</label>
                                        <div class="flex items-center gap-6">
                                            <div class="w-32 h-24 bg-blue-50 rounded-xl flex items-center justify-center overflow-hidden border border-gray-200">
                                                @if ($parametre->apropos_image_path)
                                                    <img src="{{ asset('storage/' . $parametre->apropos_image_path) }}" alt="À propos" class="w-full h-full object-cover">
                                                @else
                                                    <span class="material-icons text-blue-400 text-4xl">image</span>
                                                @endif
                                            </div>
                                            <div>
                                                <label class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium cursor-pointer inline-block">
                                                    Choisir une image
                                                    <input type="file" name="apropos_image" accept="image/png,image/jpeg" class="hidden" onchange="previewFile(this, 'apropos-preview')">
                                                </label>
                                                <p class="text-sm text-gray-500 mt-1">Format PNG ou JPG, max 2 Mo</p>
                                                <p id="apropos-preview" class="text-sm text-green-600 mt-1 hidden"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Texte À propos -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Texte de présentation</label>
                                        <textarea name="apropos_texte" rows="5"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                            placeholder="Présentez votre entreprise, son histoire, ses valeurs...">{{ old('apropos_texte', $parametre->apropos_texte) }}</textarea>
                                    </div>

                                    <!-- Mission & Vision -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Notre mission</label>
                                            <textarea name="apropos_mission" rows="3"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="La mission de votre entreprise...">{{ old('apropos_mission', $parametre->apropos_mission) }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Notre vision</label>
                                            <textarea name="apropos_vision" rows="3"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                placeholder="La vision de votre entreprise...">{{ old('apropos_vision', $parametre->apropos_vision) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Chiffres clés -->
                                    <div class="pt-6 border-t border-gray-200">
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-blue-600">analytics</span>
                                            Chiffres clés
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Année de création</label>
                                                <input type="number" name="apropos_annee_creation" min="1900"
                                                    max="{{ date('Y') }}"
                                                    value="{{ old('apropos_annee_creation', $parametre->apropos_annee_creation) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: 2010">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre d'employés</label>
                                                <input type="number" name="apropos_nombre_employes" min="1"
                                                    value="{{ old('apropos_nombre_employes', $parametre->apropos_nombre_employes) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: 50">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Horaires & Réseaux sociaux -->
                                    <div class="pt-6 border-t border-gray-200">
                                        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                            <span class="material-icons text-green-600">schedule</span>
                                            Horaires & Réseaux sociaux
                                        </h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Horaires d'ouverture</label>
                                                <input type="text" name="horaires_ouverture"
                                                    value="{{ old('horaires_ouverture', $parametre->horaires_ouverture) }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                    placeholder="Ex: Lun-Ven: 8h-18h, Sam: 9h-13h">
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                                    <input type="url" name="facebook_url"
                                                        value="{{ old('facebook_url', $parametre->facebook_url) }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                        placeholder="https://facebook.com/...">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter / X</label>
                                                    <input type="url" name="twitter_url"
                                                        value="{{ old('twitter_url', $parametre->twitter_url) }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                        placeholder="https://x.com/...">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                                                    <input type="url" name="linkedin_url"
                                                        value="{{ old('linkedin_url', $parametre->linkedin_url) }}"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                                        placeholder="https://linkedin.com/company/...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bouton sauvegarder -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="submit"
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-gray-600', 'hover:bg-gray-50');
            });
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            event.target.closest('.tab-button').classList.add('active');
            event.target.closest('.tab-button').classList.remove('text-gray-600', 'hover:bg-gray-50');
        }

        function previewFile(input, previewId) {
            const el = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                el.textContent = 'Fichier sélectionné : ' + input.files[0].name;
                el.classList.remove('hidden');
            }
        }
    </script>
@endsection
