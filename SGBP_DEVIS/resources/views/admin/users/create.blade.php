@extends('admin.layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Nouvel utilisateur</h2>
                        <p class="text-xs md:text-sm text-gray-500">Créer un nouveau compte utilisateur</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-600">
                    <span class="material-icons text-lg">info</span>
                    <span>Les champs avec <span class="text-red-500">*</span> sont obligatoires</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8 max-w-5xl mx-auto">
            <!-- Message info mobile -->
            <div class="md:hidden mb-4 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="material-icons text-blue-600 text-lg">info</span>
                    <p class="text-sm text-blue-800">Les champs avec <span class="text-red-500">*</span> sont obligatoires</p>
                </div>
            </div>

            <!-- Aide sur les exigences du mot de passe -->
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="material-icons text-yellow-600 mt-0.5">lightbulb</span>
                    <div>
                        <p class="font-medium text-yellow-800 mb-1">Exigences du mot de passe</p>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li class="flex items-center gap-2">
                                <span class="material-icons text-xs">check</span>
                                <span>Minimum 8 caractères</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-icons text-xs">check</span>
                                <span>Au moins une lettre majuscule</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-icons text-xs">check</span>
                                <span>Au moins une lettre minuscule</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-icons text-xs">check</span>
                                <span>Au moins un chiffre</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-icons text-xs">check</span>
                                <span>Au moins un symbole (!@#$%^&*)</span>
                            </li>
                        </ul>
                        <p class="text-xs text-yellow-600 mt-2">Exemple de mot de passe valide : <code class="bg-yellow-100 px-2 py-0.5 rounded">Password@123</code></p>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            @include('admin.users.form')
        </div>
    </div>
@endsection