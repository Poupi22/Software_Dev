@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 {{ $client->type === 'societe' ? 'bg-purple-600' : 'bg-green-600' }} rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($client->nom_complet, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $client->nom_complet }}</h2>
                            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500">
                                <span
                                    class="px-2 py-0.5 {{ $client->type === 'societe' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }} rounded-full">
                                    {{ $client->type_display }}
                                </span>
                                @if ($client->actif)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Actif</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full">Inactif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('clients.update')
                        <a href="{{ route('admin.clients.edit', $client) }}"
                            class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <span class="material-icons text-xl">edit</span>
                            <span class="hidden md:inline">Modifier</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 md:p-8 content-with-mobile-nav">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Colonne principale -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Informations client -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span
                                    class="material-icons text-blue-600">{{ $client->type === 'particulier' ? 'person' : 'business' }}</span>
                                Informations {{ $client->type === 'particulier' ? 'personnelles' : 'de la société' }}
                            </h3>
                            <div class="space-y-4">
                                @if ($client->type === 'particulier')
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                        <span class="text-sm font-medium text-gray-500">Nom complet</span>
                                        <span
                                            class="col-span-2 text-sm font-medium text-gray-900">{{ $client->nom_complet }}</span>
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                        <span class="text-sm font-medium text-gray-500">Raison sociale</span>
                                        <span
                                            class="col-span-2 text-sm font-medium text-gray-900">{{ $client->raison_sociale }}</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                        <span class="text-sm font-medium text-gray-500">RCCM</span>
                                        <span class="col-span-2 text-sm text-gray-900">{{ $client->rccm }}</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                        <span class="text-sm font-medium text-gray-500">NIU/NIF</span>
                                        <span class="col-span-2 text-sm text-gray-900">{{ $client->nif }}</span>
                                    </div>
                                    @if ($client->representant_legal)
                                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                            <span class="text-sm font-medium text-gray-500">Représentant</span>
                                            <span
                                                class="col-span-2 text-sm text-gray-900">{{ $client->representant_legal }}</span>
                                        </div>
                                    @endif
                                @endif

                                <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                    <span class="text-sm font-medium text-gray-500">Email</span>
                                    <a href="mailto:{{ $client->email }}"
                                        class="col-span-2 text-sm text-blue-600 hover:underline">{{ $client->email ?? 'Non renseigné' }}</a>
                                </div>
                                <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                    <span class="text-sm font-medium text-gray-500">Téléphone</span>
                                    <a href="tel:{{ $client->telephone_principal }}"
                                        class="col-span-2 text-sm text-blue-600 hover:underline">{{ $client->telephone_principal }}</a>
                                </div>
                                @if ($client->adresse)
                                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                                        <span class="text-sm font-medium text-gray-500">Adresse</span>
                                        <span class="col-span-2 text-sm text-gray-900">{{ $client->adresse }}</span>
                                    </div>
                                @endif
                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <span class="text-sm font-medium text-gray-500">Localisation</span>
                                    <span class="col-span-2 text-sm text-gray-900">{{ $client->ville ?? '-' }},
                                        {{ $client->pays ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Documents</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <p class="text-3xl font-bold text-blue-600">{{ $client->devis->count() }}</p>
                                    <p class="text-sm text-gray-600">Devis</p>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <p class="text-3xl font-bold text-green-600">{{ $client->factures->count() }}</p>
                                    <p class="text-sm text-gray-600">Factures</p>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <p class="text-3xl font-bold text-purple-600">{{ $client->pvs->count() }}</p>
                                    <p class="text-sm text-gray-600">PV</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if ($client->notes)
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <span class="material-icons text-blue-600">notes</span>
                                    Notes
                                </h3>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $client->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Colonne latérale -->
                    <div class="space-y-6">

                        <!-- CA Total -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-blue-100">Chiffre d'affaires total</span>
                                <span class="material-icons text-blue-200">account_balance</span>
                            </div>
                            {{-- <p class="text-3xl font-bold">{{ number_format($caTotal ?? 0, 0, ',', ' ') }}</p> --}}
                            <p class="text-3xl font-bold">-</p>
                            <p class="text-sm text-blue-100">FCFA</p>
                        </div>

                        <!-- Infos système -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4">Informations</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Créé le :</span>
                                    <span class="font-medium">{{ $client->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Modifié le :</span>
                                    <span class="font-medium">{{ $client->updated_at->format('d/m/Y') }}</span>
                                </div>
                                @role('admin|superadmin')
                                    @if ($client->createdBy)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Créé par :</span>
                                            <span class="font-medium">{{ $client->createdBy->nom_complet }}</span>
                                        </div>
                                    @endif
                                    @if ($client->updatedBy)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Modifié par :</span>
                                            <span class="font-medium">{{ $client->updatedBy->nom_complet }}</span>
                                        </div>
                                    @endif
                                @endrole
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4">Actions rapides</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.clients.index') }}"
                                    class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <span class="material-icons text-sm">list</span>
                                        Tous les clients
                                    </span>
                                    <span class="material-icons text-gray-400">arrow_forward</span>
                                </a>
                                @can('clients.update')
                                    <a href="{{ route('admin.clients.edit', $client) }}"
                                        class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                        <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <span class="material-icons text-sm">edit</span>
                                            Modifier
                                        </span>
                                        <span class="material-icons text-gray-400">arrow_forward</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
