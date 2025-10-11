@extends('admin.layouts.app')
@section('title', 'Factures')
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Factures</h2>
                    <p class="text-xs md:text-sm text-gray-500">Gérez toutes vos factures</p>
                </div>
                @can('factures.create')
                    <a href="{{ route('admin.factures.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouvelle facture</span>
                    </a>
                @endcan
            </div>
        </div>

        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2"><span
                        class="material-icons">check_circle</span>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form method="GET" action="{{ route('admin.factures.index') }}">
                    <div class="relative mb-4">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par numéro, titre, client..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
                    </div>

                    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                        <a href="{{ route('admin.factures.index') }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->hasAny(['statut', 'type', 'statut_paiement']) ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Toutes ({{ \App\Models\Facture::count() }})
                        </a>
                        <a href="{{ route('admin.factures.index', ['statut_paiement' => 'non_paye']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut_paiement') === 'non_paye' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Non payées ({{ \App\Models\Facture::where('statut_paiement', 'non_paye')->count() }})
                        </a>
                        <a href="{{ route('admin.factures.index', ['statut_paiement' => 'partiel']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut_paiement') === 'partiel' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Partielles ({{ \App\Models\Facture::where('statut_paiement', 'partiel')->count() }})
                        </a>
                        <a href="{{ route('admin.factures.index', ['statut_paiement' => 'paye']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut_paiement') === 'paye' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Payées ({{ \App\Models\Facture::where('statut_paiement', 'paye')->count() }})
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <select name="statut" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon
                            </option>
                            <option value="envoye" {{ request('statut') === 'envoye' ? 'selected' : '' }}>Envoyée</option>
                            <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        <select name="type" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500">
                            <option value="">Tous les types</option>
                            <option value="provisoire" {{ request('type') === 'provisoire' ? 'selected' : '' }}>Provisoire
                            </option>
                            <option value="final" {{ request('type') === 'final' ? 'selected' : '' }}>Finale</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Table Desktop -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Numéro</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Devis lié</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Émission</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Échéance</th>
                            <th class="text-right px-6 py-4 text-xs font-medium text-gray-500 uppercase">Total TTC</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Paiement</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($factures as $facture)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons text-green-600">receipt_long</span>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $facture->numero }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($facture->type) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $facture->client->nom_complet ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $facture->client->telephone_principal ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($facture->devis)
                                        <a href="{{ route('admin.devis.show', $facture->devis) }}"
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:underline">
                                            <span class="material-icons text-sm">description</span>
                                            <span class="text-sm font-medium">{{ $facture->devis->numero }}</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $facture->date_emission->format('d/m/Y') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm {{ $facture->date_echeance->isPast() && $facture->statut_paiement !== 'paye' ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                    {{ $facture->date_echeance->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    {{ number_format($facture->total_ttc, 0, ',', ' ') }} {{ $facture->devise }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($facture->statut_paiement === 'paye')
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Payée</span>
                                    @elseif($facture->statut_paiement === 'partiel')
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Partielle</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Non
                                            payée</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.factures.show', $facture) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Voir">
                                            <span class="material-icons text-lg">visibility</span>
                                        </a>
                                        @if ($facture->statut === 'brouillon')
                                            @can('factures.update')
                                                <a href="{{ route('admin.factures.edit', $facture) }}"
                                                    class="p-1.5 text-gray-600 hover:bg-gray-50 rounded-lg" title="Modifier">
                                                    <span class="material-icons text-lg">edit</span>
                                                </a>
                                            @endcan
                                        @endif
                                        @can('factures.send')
                                            <a href="{{ route('admin.factures.show', $facture) }}?send=1"
                                                class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Envoyer">
                                                <span class="material-icons text-lg">send</span>
                                            </a>
                                        @endcan
                                        <a href="{{ route('admin.factures.pdf', $facture) }}"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="PDF">
                                            <span class="material-icons text-lg">picture_as_pdf</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <span class="material-icons text-4xl text-gray-300 block mb-2">receipt_long</span>
                                    Aucune facture trouvée
                                    @can('factures.create')
                                        <br><a href="{{ route('admin.factures.create') }}"
                                            class="text-green-600 hover:underline mt-2 inline-block">Créer une facture</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-3">
                @forelse($factures as $facture)
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-gray-800">{{ $facture->numero }}</span>
                            @if ($facture->statut_paiement === 'paye')
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Payée</span>
                            @elseif($facture->statut_paiement === 'partiel')
                                <span
                                    class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Partielle</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Non
                                    payée</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">{{ $facture->client->nom_complet ?? '-' }}</p>
                        @if ($facture->devis)
                            <a href="{{ route('admin.devis.show', $facture->devis) }}"
                                class="inline-flex items-center gap-1 text-blue-600 hover:underline text-xs mt-1">
                                <span class="material-icons text-sm">description</span>
                                Devis {{ $facture->devis->numero }}
                            </a>
                        @endif
                        <p class="text-lg font-bold text-green-600 mt-1">
                            {{ number_format($facture->total_ttc, 0, ',', ' ') }} {{ $facture->devise }}</p>
                        <div class="flex items-center gap-3 mt-3">
                            <a href="{{ route('admin.factures.show', $facture) }}"
                                class="flex-1 text-center py-2 border border-gray-300 rounded-lg text-sm">Voir</a>
                            @can('factures.send')
                                <a href="{{ route('admin.factures.show', $facture) }}?send=1"
                                    class="flex-1 text-center py-2 bg-green-50 text-green-600 border border-green-200 rounded-lg text-sm">Envoyer</a>
                            @endcan
                            <a href="{{ route('admin.factures.pdf', $facture) }}"
                                class="flex-1 text-center py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm">PDF</a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-8 text-center text-gray-500">Aucune facture</div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $factures->links() }}
            </div>
        </div>
    </div>
@endsection
