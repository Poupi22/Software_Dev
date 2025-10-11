@extends('admin.layouts.app')
@section('content')
    <div class="content-with-mobile-nav p-4 md:p-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                        <span class="material-icons text-blue-600">description</span>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['devis'] }}</p>
                <p class="text-sm text-gray-500">Devis créés</p>
            </div>

            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                        <span class="material-icons text-green-600">receipt</span>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $stats['factures'] }}</p>
                <p class="text-sm text-gray-500">Factures émises</p>
            </div>

            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                        <span class="material-icons text-purple-600">trending_up</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-800 mb-1">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FCFA
                </p>
                <p class="text-sm text-gray-500">Chiffre d'affaires</p>
            </div>

            <div class="stat-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                        <span class="material-icons text-orange-600">percent</span>
                    </div>
                </div>
                @php
                    $tauxConversion =
                        $stats['devis'] > 0 ? round(($stats['devis_acceptes'] / $stats['devis']) * 100) : 0;
                @endphp
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $tauxConversion }}%</p>
                <p class="text-sm text-gray-500">Conversion devis</p>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-indigo-600">people</span>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['clients'] }}</p>
                    <p class="text-xs text-gray-500">Clients</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-orange-600">person_add</span>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['prospects'] }}</p>
                    <p class="text-xs text-gray-500">Nouveaux prospects</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-green-600">paid</span>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['factures_payees'] }}</p>
                    <p class="text-xs text-gray-500">Factures payées</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="material-icons text-red-600">pending</span>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats['ca_en_attente'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500">En attente (FCFA)</p>
                </div>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Devis -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Derniers devis</h3>
                    <a href="{{ route('admin.devis.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                <div class="space-y-3">
                    @forelse($devis_recents as $devis)
                        <a href="{{ route('admin.devis.show', $devis) }}"
                            class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                                <span class="material-icons text-blue-600 text-xl">description</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm">{{ $devis->numero }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $devis->client->nom_complet ?? 'N/A' }} •
                                    {{ number_format($devis->total_ttc, 0, ',', ' ') }} FCFA</p>
                            </div>
                            @if ($devis->type === 'provisoire')
                                <span
                                    class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-medium">Provisoire</span>
                            @else
                                <span
                                    class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Final</span>
                            @endif
                        </a>
                    @empty
                        <div class="text-center py-6 text-gray-400">
                            <span class="material-icons text-4xl mb-2">description</span>
                            <p class="text-sm">Aucun devis pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Factures -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Dernières factures</h3>
                    <a href="{{ route('admin.factures.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir tout</a>
                </div>
                <div class="space-y-3">
                    @forelse($factures_recentes as $facture)
                        <a href="{{ route('admin.factures.show', $facture) }}"
                            class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                                <span class="material-icons text-green-600 text-xl">receipt</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm">{{ $facture->numero }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $facture->client->nom_complet ?? 'N/A' }} •
                                    {{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA</p>
                            </div>
                            @if ($facture->statut_paiement === 'paye')
                                <span
                                    class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Payée</span>
                            @elseif($facture->type === 'provisoire')
                                <span
                                    class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-medium">Provisoire</span>
                            @else
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">En
                                    attente</span>
                            @endif
                        </a>
                    @empty
                        <div class="text-center py-6 text-gray-400">
                            <span class="material-icons text-4xl mb-2">receipt</span>
                            <p class="text-sm">Aucune facture pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        @if ($stats['prospects'] > 0)
            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mt-6 rounded-lg">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-orange-500">person_add</span>
                    <div class="flex-1">
                        <p class="text-sm text-orange-800">
                            <strong>{{ $stats['prospects'] }}</strong> nouveau{{ $stats['prospects'] > 1 ? 'x' : '' }}
                            prospect{{ $stats['prospects'] > 1 ? 's' : '' }} en attente de traitement
                        </p>
                    </div>
                    <a href="{{ route('admin.prospects.index', ['statut' => 'nouveau']) }}"
                        class="text-orange-600 hover:text-orange-800">
                        <span class="material-icons">chevron_right</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
