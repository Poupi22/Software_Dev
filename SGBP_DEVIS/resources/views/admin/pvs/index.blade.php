@extends('admin.layouts.app')
@section('title', 'PV de Réception')
@section('content')

    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">PV de Réception</h2>
                        <p class="text-xs md:text-sm text-gray-500">Procès-verbaux de réception des travaux</p>
                    </div>
                </div>
                @can('pvs.create')
                    <a href="{{ route('admin.factures.index') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                        title="Créez un PV depuis la page détail d'une facture payée">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouveau PV</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>{{ session('success') }}
                </p>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700 flex items-center gap-2">
                    <span class="material-icons">error</span>{{ session('error') }}
                </p>
            </div>
        @endif

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form method="GET" action="{{ route('admin.pvs.index') }}">
                    <div class="relative mb-4">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par numéro, titre, client..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>

                    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                        <a href="{{ route('admin.pvs.index') }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->hasAny(['statut', 'etat_travaux']) ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Tous ({{ \App\Models\Pv::count() }})
                        </a>
                        <a href="{{ route('admin.pvs.index', ['statut' => 'brouillon']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut') === 'brouillon' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Brouillons ({{ \App\Models\Pv::where('statut', 'brouillon')->count() }})
                        </a>
                        <a href="{{ route('admin.pvs.index', ['statut' => 'signe']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut') === 'signe' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Signés ({{ \App\Models\Pv::where('statut', 'signe')->count() }})
                        </a>
                        <a href="{{ route('admin.pvs.index', ['statut' => 'archive']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut') === 'archive' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Archivés ({{ \App\Models\Pv::where('statut', 'archive')->count() }})
                        </a>
                    </div>

                    <select name="etat_travaux" onchange="this.form.submit()"
                        class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                        <option value="">Tous les états</option>
                        <option value="conforme" {{ request('etat_travaux') === 'conforme' ? 'selected' : '' }}>Conforme
                        </option>
                        <option value="reserve_mineure"
                            {{ request('etat_travaux') === 'reserve_mineure' ? 'selected' : '' }}>Réserve mineure</option>
                        <option value="reserve_majeure"
                            {{ request('etat_travaux') === 'reserve_majeure' ? 'selected' : '' }}>Réserve majeure</option>
                        <option value="non_conforme" {{ request('etat_travaux') === 'non_conforme' ? 'selected' : '' }}>Non
                            conforme</option>
                    </select>
                </form>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Numéro</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Facture liée</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Date réception</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">État</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $etatStyles = [
                                'conforme' => 'bg-green-100 text-green-700',
                                'reserve_mineure' => 'bg-yellow-100 text-yellow-700',
                                'reserve_majeure' => 'bg-orange-100 text-orange-700',
                                'non_conforme' => 'bg-red-100 text-red-700',
                            ];
                            $etatLabels = [
                                'conforme' => 'Conforme',
                                'reserve_mineure' => 'Rés. mineure',
                                'reserve_majeure' => 'Rés. majeure',
                                'non_conforme' => 'Non conforme',
                            ];
                            $statutStyles = [
                                'brouillon' => 'bg-gray-100 text-gray-700',
                                'signe' => 'bg-green-100 text-green-700',
                                'archive' => 'bg-blue-100 text-blue-700',
                            ];
                        @endphp
                        @forelse($pvs as $pv)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600">verified</span>
                                        <span class="font-medium text-gray-800">{{ $pv->numero }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $pv->client->nom_complet ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">{{ $pv->client->email ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($pv->facture?->devis?->bonCommande?->numero)
                                        <span class="text-sm font-medium text-gray-800">{{ $pv->facture->devis->bonCommande->numero }}</span>
                                    @elseif ($pv->facture)
                                        <a href="{{ route('admin.factures.show', $pv->facture) }}"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                            <span>{{ $pv->facture->numero }}</span>
                                            <span class="material-icons text-sm">open_in_new</span>
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $pv->date_reception ? $pv->date_reception->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium {{ $etatStyles[$pv->etat_travaux] ?? 'bg-gray-100 text-gray-700' }} rounded-full">
                                        {{ $etatLabels[$pv->etat_travaux] ?? $pv->etat_travaux }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium {{ $statutStyles[$pv->statut] ?? 'bg-gray-100 text-gray-700' }} rounded-full">
                                        {{ ucfirst($pv->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.pvs.show', $pv) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Voir">
                                            <span class="material-icons text-xl">visibility</span>
                                        </a>
                                        @can('pvs.update')
                                            @if ($pv->statut === 'brouillon')
                                                <a href="{{ route('admin.pvs.edit', $pv) }}"
                                                    class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg" title="Modifier">
                                                    <span class="material-icons text-xl">edit</span>
                                                </a>
                                                <form action="{{ route('admin.pvs.signer', $pv) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Signer ce PV ? Il ne pourra plus être modifié.');">
                                                    @csrf @method('PATCH')
                                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg"
                                                        title="Signer">
                                                        <span class="material-icons text-xl">draw</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        <a href="{{ route('admin.pvs.pdf', $pv) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Télécharger PDF">
                                            <span class="material-icons text-xl">picture_as_pdf</span>
                                        </a>
                                        @can('pvs.delete')
                                            @if ($pv->statut === 'brouillon')
                                                <form action="{{ route('admin.pvs.destroy', $pv) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Supprimer ce PV ?');">
                                                    @csrf @method('DELETE')
                                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                                        title="Supprimer">
                                                        <span class="material-icons text-xl">delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">assignment</span>
                                    <p>Aucun PV de réception trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($pvs->hasPages())
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">{{ $pvs->firstItem() }} à {{ $pvs->lastItem() }} sur
                            {{ $pvs->total() }}</p>
                        {{ $pvs->links() }}
                    </div>
                @endif
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @forelse($pvs as $pv)
                    @php
                        $borderColor = match ($pv->statut) {
                            'signe' => 'border-green-500',
                            'archive' => 'border-blue-500',
                            default => 'border-gray-300',
                        };
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $borderColor }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="material-icons text-purple-600">verified</span>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $pv->numero }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $pv->date_reception ? $pv->date_reception->format('d/m/Y') : '' }}</p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium {{ $statutStyles[$pv->statut] ?? 'bg-gray-100 text-gray-700' }} rounded-full">
                                {{ ucfirst($pv->statut) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <p class="font-medium text-gray-800">{{ $pv->client->nom_complet ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $pv->titre }}</p>
                        </div>
                        @if ($pv->facture)
                            <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg mb-3">
                                <span class="material-icons text-blue-600 text-sm">receipt</span>
                                @if ($pv->facture->devis?->bonCommande?->numero)
                                    <span class="text-sm text-blue-600 font-medium">{{ $pv->facture->devis->bonCommande->numero }}</span>
                                @else
                                    <a href="{{ route('admin.factures.show', $pv->facture) }}"
                                        class="text-sm text-blue-600 font-medium">
                                        {{ $pv->facture->numero }}
                                    </a>
                                @endif
                            </div>
                        @endif
                        <div class="flex gap-2 pt-3 border-t flex-wrap">
                            <a href="{{ route('admin.pvs.show', $pv) }}"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                                <span class="material-icons text-lg">visibility</span><span>Voir</span>
                            </a>
                            <a href="{{ route('admin.pvs.pdf', $pv) }}"
                                class="flex items-center justify-center w-10 h-10 text-gray-600 hover:bg-gray-50 rounded-lg">
                                <span class="material-icons">picture_as_pdf</span>
                            </a>
                            @can('pvs.delete')
                                @if ($pv->statut === 'brouillon')
                                    <form action="{{ route('admin.pvs.destroy', $pv) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce PV ?');">
                                        @csrf @method('DELETE')
                                        <button
                                            class="flex items-center justify-center w-10 h-10 text-red-600 hover:bg-red-50 rounded-lg">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-500">
                        <span class="material-icons text-4xl text-gray-300">assignment</span>
                        <p class="mt-2">Aucun PV de réception</p>
                    </div>
                @endforelse

                @if ($pvs->hasPages())
                    <div class="mt-4">{{ $pvs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
