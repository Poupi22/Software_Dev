@extends('layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient py-16 md:py-20">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Contactez-nous</h1>
            <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                Une question ? Un projet ? Notre équipe est à votre écoute pour vous accompagner
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Contact Info -->
                <div class="space-y-6">
                    <!-- Info Card 1 -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="material-icons text-blue-600 text-3xl">location_on</span>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Notre adresse</h3>
                        <p class="text-gray-600">
                            {{ $parametre->adresse ?? '' }}<br>
                            {{ implode(', ', array_filter([$parametre->ville ?? '', $parametre->pays ?? ''])) }}
                        </p>
                    </div>

                    <!-- Info Card 2 -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="material-icons text-green-600 text-3xl">phone</span>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Téléphone</h3>
                        <p class="text-gray-600 mb-2">{{ $parametre->telephone ?? '' }}</p>
                        @if ($parametre->telephone_secondaire)
                            <p class="text-gray-600">{{ $parametre->telephone_secondaire }}</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">{{ $parametre->horaires_ouverture ?? 'Lun-Ven: 8h-18h' }}</p>
                    </div>

                    <!-- Info Card 3 -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="material-icons text-purple-600 text-3xl">email</span>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Email</h3>
                        <p class="text-gray-600 mb-1">{{ $parametre->email ?? '' }}</p>
                        <p class="text-sm text-gray-500 mt-2">Réponse sous 24h</p>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Suivez-nous</h3>
                        <div class="flex items-center gap-3">
                            @if ($parametre->facebook_url)
                                <a href="{{ $parametre->facebook_url }}" target="_blank"
                                    class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700">
                                    <span class="material-icons">facebook</span>
                                </a>
                            @endif
                            @if ($parametre->twitter_url)
                                <a href="{{ $parametre->twitter_url }}" target="_blank"
                                    class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500">
                                    <span class="material-icons">share</span>
                                </a>
                            @endif
                            @if ($parametre->linkedin_url)
                                <a href="{{ $parametre->linkedin_url }}" target="_blank"
                                    class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-blue-800">
                                    <span class="material-icons">business</span>
                                </a>
                            @endif
                            @if (!$parametre->facebook_url && !$parametre->twitter_url && !$parametre->linkedin_url)
                                <p class="text-gray-500 text-sm">Bientôt disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Envoyez-nous un message</h2>
                            <p class="text-gray-600">Remplissez le formulaire ci-dessous et nous vous répondrons dans les
                                plus brefs délais</p>
                        </div>

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                                <span class="material-icons text-green-600">check_circle</span>
                                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-icons text-red-600">error</span>
                                    <p class="text-red-700 font-medium">Veuillez corriger les erreurs suivantes :</p>
                                </div>
                                <ul class="list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Nom Entreprise -->
                            <div id="entrepriseField" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom de l'entreprise
                                </label>
                                <input type="text" name="entreprise" value="{{ old('entreprise') }}"
                                    placeholder="Ex: Mon Entreprise SARL"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('entreprise') border-red-500 @enderror">
                                @error('entreprise')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nom et Prénom -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom complet *
                                    </label>
                                    <input type="text" name="nom" value="{{ old('nom') }}"
                                        placeholder="Votre nom" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nom') border-red-500 @enderror">
                                    @error('nom')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénom
                                    </label>
                                    <input type="text" name="prenom" value="{{ old('prenom') }}"
                                        placeholder="Votre prénom"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('prenom') border-red-500 @enderror">
                                    @error('prenom')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email et Téléphone -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email *
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">email</span>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            placeholder="votre@email.cm" required
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Téléphone
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">phone</span>
                                        <input type="tel" name="telephone" value="{{ old('telephone') }}"
                                            placeholder="+237 6 XX XX XX XX"
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('telephone') border-red-500 @enderror">
                                    </div>
                                    @error('telephone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sujet -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sujet *
                                </label>
                                <select name="objet" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('objet') border-red-500 @enderror">
                                    <option value="">Sélectionnez un sujet...</option>
                                    <option value="Demande de devis"
                                        {{ old('objet') == 'Demande de devis' ? 'selected' : '' }}>Demande de devis
                                    </option>
                                    <option value="Information sur un service"
                                        {{ old('objet') == 'Information sur un service' ? 'selected' : '' }}>Information
                                        sur un service</option>
                                    <option value="Suivi de projet"
                                        {{ old('objet') == 'Suivi de projet' ? 'selected' : '' }}>Suivi de projet</option>
                                    <option value="Réclamation" {{ old('objet') == 'Réclamation' ? 'selected' : '' }}>
                                        Réclamation</option>
                                    <option value="Autre" {{ old('objet') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('objet')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Votre message *
                                </label>
                                <textarea rows="5" name="message" placeholder="Décrivez votre projet ou votre demande..." required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Minimum 20 caractères</p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-lg transition-colors">
                                <span class="material-icons">send</span>
                                <span>Envoyer le message</span>
                            </button>

                            <p class="text-sm text-center text-gray-500">
                                Nous vous répondrons dans un délai de 24 heures ouvrées
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Où nous trouver</h2>
                <p class="text-gray-600">Notre bureau est situé au cœur de Douala</p>
            </div>
            <div class="max-w-5xl mx-auto">
                <!-- Placeholder for Google Maps -->
                <div class="bg-gray-200 rounded-2xl overflow-hidden h-96 flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-icons text-gray-400 text-6xl mb-4">map</span>
                        <p class="text-gray-600 font-medium">Carte Google Maps</p>
                        <p class="text-sm text-gray-500">Akwa, Douala, Cameroun</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Si old('entreprise') a une valeur, afficher le champ entreprise
        @if (old('entreprise'))
            document.getElementById('entrepriseField').classList.remove('hidden');
        @endif
    </script>
@endsection
