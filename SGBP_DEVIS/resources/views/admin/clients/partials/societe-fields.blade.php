@php
    $isEdit = isset($client) && $client->exists;

    $pays = [
        'GA' => '🇬🇦 Gabon',
        'CM' => '🇨🇲 Cameroun',
        'CG' => '🇨🇬 Congo',
        'CD' => '🇨🇩 RD Congo',
        'CI' => '🇨🇮 Côte d\'Ivoire',
        'SN' => '🇸🇳 Sénégal',
        'ML' => '🇲🇱 Mali',
        'BF' => '🇧🇫 Burkina Faso',
        'GN' => '🇬🇳 Guinée',
        'GQ' => '🇬🇶 Guinée Équatoriale',
        'CF' => '🇨🇫 Centrafrique',
        'TD' => '🇹🇩 Tchad',
        'NE' => '🇳🇪 Niger',
        'BJ' => '🇧🇯 Bénin',
        'TG' => '🇹🇬 Togo',
        'GH' => '🇬🇭 Ghana',
        'NG' => '🇳🇬 Nigeria',
        'MG' => '🇲🇬 Madagascar',
        'MA' => '🇲🇦 Maroc',
        'DZ' => '🇩🇿 Algérie',
        'TN' => '🇹🇳 Tunisie',
        'EG' => '🇪🇬 Égypte',
        'ZA' => '🇿🇦 Afrique du Sud',
        'KE' => '🇰🇪 Kenya',
        'ET' => '🇪🇹 Éthiopie',
        'FR' => '🇫🇷 France',
        'BE' => '🇧🇪 Belgique',
        'CH' => '🇨🇭 Suisse',
        'US' => '🇺🇸 États-Unis',
        'OTHER' => '🌍 Autre',
    ];
@endphp

<!-- Informations de la société -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-purple-600">business</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Informations de la société</h3>
            <p class="text-sm text-gray-600">Identification légale</p>
        </div>
    </div>

    <div class="space-y-5">

        {{-- Raison sociale --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Raison sociale <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="raison_sociale"
                value="{{ old('raison_sociale', $isEdit ? $client->raison_sociale : '') }}"
                placeholder="Ex: SGBP SARL"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('raison_sociale') border-red-500 @enderror"
            >
            @error('raison_sociale')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- NIF/NIU + RCCM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    NIF / NIU <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nif"
                    value="{{ old('nif', $isEdit ? $client->nif : '') }}"
                    placeholder="Ex: 000123456A"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nif') border-red-500 @enderror"
                >
                <p class="text-xs text-gray-500 mt-1">Numéro d'Identification Fiscale / Unique</p>
                @error('nif')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    RCCM
                </label>
                <input
                    type="text"
                    name="rccm"
                    value="{{ old('rccm', $isEdit ? $client->rccm : '') }}"
                    placeholder="Ex: GA-LBV-2023-B-12345"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('rccm') border-red-500 @enderror"
                >
                <p class="text-xs text-gray-500 mt-1">Registre du Commerce et du Crédit Mobilier</p>
                @error('rccm')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Boîte Postale --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Boîte Postale (BP) <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="bp"
                value="{{ old('bp', $isEdit ? $client->bp : '') }}"
                placeholder="Ex: BP 1234 Libreville"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('bp') border-red-500 @enderror"
            >
            @error('bp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

    </div>
</div>

<!-- Représentant légal -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-green-600">badge</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Représentant légal</h3>
            <p class="text-sm text-gray-600">Personne de contact</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nom complet du représentant
            </label>
            <input
                type="text"
                name="representant_legal"
                value="{{ old('representant_legal', $isEdit ? $client->representant_legal : '') }}"
                placeholder="Ex: Jean-Pierre Moussavou"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Fonction / Titre
            </label>
            <input
                type="text"
                name="fonction_representant"
                value="{{ old('fonction_representant', $isEdit ? $client->fonction_representant : '') }}"
                placeholder="Ex: Directeur Général"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
        </div>
    </div>
</div>

<!-- Coordonnées de la société -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-blue-600">contact_mail</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Coordonnées</h3>
            <p class="text-sm text-gray-600">Contact de la société</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Email principal <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">email</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $isEdit ? $client->email : '') }}"
                    placeholder="contact@societe.ga"
                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                >
            </div>
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Téléphone <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">phone</span>
                <input
                    type="tel"
                    name="telephone_principal"
                    value="{{ old('telephone_principal', $isEdit ? $client->telephone_principal : '') }}"
                    placeholder="+241 XX XX XX XX"
                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('telephone_principal') border-red-500 @enderror"
                >
            </div>
            @error('telephone_principal')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Site web
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">language</span>
                <input
                    type="url"
                    name="site_web"
                    value="{{ old('site_web', $isEdit ? $client->site_web : '') }}"
                    placeholder="https://www.societe.ga"
                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
            </div>
        </div>
    </div>
</div>

<!-- Siège social -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-orange-600">location_city</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Siège social</h3>
            <p class="text-sm text-gray-600">Adresse de la société</p>
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Adresse du siège
            </label>
            <input
                type="text"
                name="adresse"
                value="{{ old('adresse', $isEdit ? $client->adresse : '') }}"
                placeholder="Quartier, rue, immeuble..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                <input
                    type="text"
                    name="ville"
                    value="{{ old('ville', $isEdit ? $client->ville : '') }}"
                    placeholder="Ex: Libreville"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                <select name="pays" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Sélectionner...</option>
                    @foreach($pays as $code => $label)
                        <option value="{{ $code }}" {{ old('pays', $isEdit ? $client->pays : 'GA') === $code ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Informations complémentaires -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-indigo-600">category</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Informations complémentaires</h3>
            <p class="text-sm text-gray-600">Secteur et notes</p>
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Secteur d'activité</label>
            <select name="secteur_activite" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">Sélectionner...</option>
                <option value="BTP / Construction"            {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'BTP / Construction'            ? 'selected' : '' }}>🏗️ BTP / Construction</option>
                <option value="Pétrole / Mines"               {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Pétrole / Mines'               ? 'selected' : '' }}>⛽ Pétrole / Mines</option>
                <option value="Informatique / Technologie"    {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Informatique / Technologie'    ? 'selected' : '' }}>💻 Informatique / Technologie</option>
                <option value="Commerce / Distribution"       {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Commerce / Distribution'       ? 'selected' : '' }}>🛒 Commerce / Distribution</option>
                <option value="Services aux entreprises"      {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Services aux entreprises'      ? 'selected' : '' }}>🏢 Services aux entreprises</option>
                <option value="Industrie / Manufacturing"     {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Industrie / Manufacturing'     ? 'selected' : '' }}>🏭 Industrie / Manufacturing</option>
                <option value="Agriculture / Agro-alimentaire"{{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Agriculture / Agro-alimentaire'? 'selected' : '' }}>🌾 Agriculture / Agro-alimentaire</option>
                <option value="Santé / Médical"               {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Santé / Médical'               ? 'selected' : '' }}>🏥 Santé / Médical</option>
                <option value="Éducation / Formation"         {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Éducation / Formation'         ? 'selected' : '' }}>📚 Éducation / Formation</option>
                <option value="Transport / Logistique"        {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Transport / Logistique'        ? 'selected' : '' }}>🚗 Transport / Logistique</option>
                <option value="Hôtellerie / Restauration"     {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Hôtellerie / Restauration'     ? 'selected' : '' }}>🏨 Hôtellerie / Restauration</option>
                <option value="Télécommunications"            {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Télécommunications'            ? 'selected' : '' }}>📱 Télécommunications</option>
                <option value="Énergie / Utilities"           {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Énergie / Utilities'           ? 'selected' : '' }}>⚡ Énergie / Utilities</option>
                <option value="Autre"                         {{ old('secteur_activite', $isEdit ? $client->secteur_activite : '') === 'Autre'                         ? 'selected' : '' }}>🎨 Autre</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description / Notes</label>
            <textarea
                name="notes"
                rows="4"
                placeholder="Informations supplémentaires sur la société..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
            >{{ old('notes', $isEdit ? $client->notes : '') }}</textarea>
        </div>
    </div>
</div>