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
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                            Modifier l'utilisateur
                            @if($user->isSuperAdmin())
                                <span class="material-icons text-yellow-500 text-xl" title="Super Admin">shield</span>
                            @endif
                        </h2>
                        <p class="text-xs md:text-sm text-gray-500">{{ $user->nom_complet }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($user->isSuperAdmin())
                        <span class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium">
                            <span class="material-icons text-lg">shield</span>
                            <span>Super Admin</span>
                        </span>
                    @endif
                    @if($user->is_active)
                        <span class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span>Actif</span>
                        </span>
                    @else
                        <span class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Inactif</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8 max-w-5xl mx-auto">
            <!-- Protection Super Admin -->
            @if($user->isSuperAdmin() && !auth()->user()->isSuperAdmin())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-icons text-red-500 mt-0.5">warning</span>
                        <div>
                            <p class="font-medium text-red-800 mb-1">Accès restreint</p>
                            <p class="text-sm text-red-700">
                                Seul le Super Admin peut modifier ce compte. Vous pouvez uniquement consulter les informations.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informations en lecture seule -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="material-icons text-blue-600">person</span>
                            Informations personnelles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" value="{{ $user->prenom }}" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" value="{{ $user->nom }}" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="text" value="{{ $user->email }}" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <input type="text" value="{{ $user->telephone ?? 'Non renseigné' }}" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            <span class="material-icons text-xl">arrow_back</span>
                            <span>Retour à la liste</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Aide pour modification du mot de passe -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-icons text-blue-600 mt-0.5">info</span>
                        <div>
                            <p class="font-medium text-blue-800 mb-1">Modification du mot de passe</p>
                            <p class="text-sm text-blue-700">
                                Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier. 
                                Si vous remplissez ces champs, le mot de passe doit respecter les exigences de sécurité.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                @include('admin.users.form')
            @endif
        </div>
    </div>
@endsection