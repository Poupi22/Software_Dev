@php
    $isEdit = isset($client) && $client->exists;

    // Liste des pays africains (Gabon en premier)
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

<!-- Informations personnelles -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-green-600">person</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Informations personnelles</h3>
            <p class="text-sm text-gray-600">Identité du client</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nom <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="nom"
                value="{{ old('nom', $isEdit ? $client->nom : '') }}"
                placeholder="Ex: Moussavou"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nom') border-red-500 @enderror"
            >
            @error('nom')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Prénom <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="prenom"
                value="{{ old('prenom', $isEdit ? $client->prenom : '') }}"
                placeholder="Ex: Jean-Pierre"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('prenom') border-red-500 @enderror"
            >
            @error('prenom')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<!-- Coordonnées -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-purple-600">contact_mail</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Coordonnées</h3>
            <p class="text-sm text-gray-600">Contact du client</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Email
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">email</span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $isEdit ? $client->email : '') }}"
                    placeholder="exemple@email.ga"
                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                >
            </div>
            <p class="text-xs text-gray-500 mt-1">Optionnel pour les particuliers</p>
            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Téléphone portable <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">smartphone</span>
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
                Téléphone fixe
            </label>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">phone</span>
                <input
                    type="tel"
                    name="telephone_secondaire"
                    value="{{ old('telephone_secondaire', $isEdit ? $client->telephone_secondaire : '') }}"
                    placeholder="+241 XX XX XX XX (optionnel)"
                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
            </div>
        </div>
    </div>
</div>

<!-- Adresse -->
<div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-orange-600">location_on</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Adresse</h3>
            <p class="text-sm text-gray-600">Localisation du client</p>
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Adresse complète
            </label>
            <input
                type="text"
                name="adresse"
                value="{{ old('adresse', $isEdit ? $client->adresse : '') }}"
                placeholder="Quartier, rue, boîte postale..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ville
                </label>
                <input
                    type="text"
                    name="ville"
                    value="{{ old('ville', $isEdit ? $client->ville : '') }}"
                    placeholder="Ex: Libreville"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pays
                </label>
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
            <span class="material-icons text-indigo-600">notes</span>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-800">Informations complémentaires</h3>
            <p class="text-sm text-gray-600">Notes et remarques</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Notes / Remarques
        </label>
        <textarea
            name="notes"
            rows="4"
            placeholder="Informations supplémentaires sur le client (préférences, historique, etc.)..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
        >{{ old('notes', $isEdit ? $client->notes : '') }}</textarea>
    </div>
</div>
