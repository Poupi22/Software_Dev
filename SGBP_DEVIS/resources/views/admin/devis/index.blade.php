@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Devis</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez tous vos devis</p>
                    </div>
                </div>
                @can('devis.create')
                    <a href="{{ route('admin.devis.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouveau</span>
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

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form method="GET" action="{{ route('admin.devis.index') }}">
                    <!-- Search -->
                    <div class="relative mb-4">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par numéro, client..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Filter Chips -->
                    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                        <a href="{{ route('admin.devis.index') }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->hasAny(['type', 'statut']) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Tous ({{ \App\Models\Devis::count() }})
                        </a>
                        <a href="{{ route('admin.devis.index', ['type' => 'provisoire']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'provisoire' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Provisoire ({{ \App\Models\Devis::where('type', 'provisoire')->count() }})
                        </a>
                        <a href="{{ route('admin.devis.index', ['type' => 'final']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'final' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Final ({{ \App\Models\Devis::where('type', 'final')->count() }})
                        </a>
                        <a href="{{ route('admin.devis.index', ['statut' => 'brouillon']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium {{ request('statut') === 'brouillon' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Brouillon
                        </a>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <select name="devise" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Toutes les devises</option>
                            <option value="FCFA" {{ request('devise') === 'FCFA' ? 'selected' : '' }}>FCFA</option>
                            <option value="EUR" {{ request('devise') === 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="USD" {{ request('devise') === 'USD' ? 'selected' : '' }}>USD</option>
                        </select>
                        <select name="statut" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon
                            </option>
                            <option value="envoye" {{ request('statut') === 'envoye' ? 'selected' : '' }}>Envoyé</option>
                            <option value="accepte" {{ request('statut') === 'accepte' ? 'selected' : '' }}>Accepté
                            </option>
                            <option value="refuse" {{ request('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                            <option value="expire" {{ request('statut') === 'expire' ? 'selected' : '' }}>Expiré</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Numéro</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Validité</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($devis as $devi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons text-blue-600">description</span>
                                        <span class="font-medium text-gray-800">{{ $devi->numero }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $devi->client->nom_complet }}</p>
                                    <p class="text-sm text-gray-500">{{ $devi->client->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $devi->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ number_format($devi->total_ttc, 0, ',', ' ') }}
                                        {{ $devi->devise }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @php
                                        $validiteMois = $devi->validite_mois ?? 1;
                                        $dateExpiration = $devi->created_at->copy()->addMonths($validiteMois);
                                        $daysLeft = now()->diffInDays($dateExpiration, false);
                                    @endphp
                                    <p>{{ $validiteMois }} mois</p>
                                    <p class="text-xs {{ $daysLeft < 0 ? 'text-red-500' : ($daysLeft < 7 ? 'text-orange-500' : 'text-gray-500') }}">
                                        @if ($daysLeft < 0)
                                            Expiré
                                        @elseif($daysLeft == 0)
                                            Expire aujourd'hui
                                        @else
                                            {{ round($daysLeft) }} j restants
                                        @endif
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium
                                        {{ $devi->type === 'final' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} rounded-full">
                                        {{ ucfirst($devi->type) }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-xs font-medium
                                        {{ $devi->statut === 'accepte'
                                            ? 'bg-green-100 text-green-700'
                                            : ($devi->statut === 'refuse'
                                                ? 'bg-red-100 text-red-700'
                                                : ($devi->statut === 'envoye'
                                                    ? 'bg-blue-100 text-blue-700'
                                                    : 'bg-gray-100 text-gray-700')) }}
                                        rounded-full ml-1">
                                        {{ ucfirst($devi->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        {{-- Voir --}}
                                        <a href="{{ route('admin.devis.show', $devi) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Voir">
                                            <span class="material-icons text-xl">visibility</span>
                                        </a>
                                        {{-- Modifier --}}
                                        @can('devis.update')
                                            @if ($devi->type === 'provisoire' && !in_array($devi->statut, ['accepte', 'refuse', 'expire']))
                                                <a href="{{ route('admin.devis.edit', $devi) }}"
                                                    class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg" title="Modifier">
                                                    <span class="material-icons text-xl">edit</span>
                                                </a>
                                            @endif
                                        @endcan
                                        {{-- Envoyer --}}
                                        @can('devis.send')
                                            <a href="{{ route('admin.devis.show', $devi) }}?send=1"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg"
                                                title="Envoyer par email">
                                                <span class="material-icons text-xl">send</span>
                                            </a>
                                        @endcan
                                        {{-- Finaliser --}}
                                        @can('devis.update')
                                            @if ($devi->type === 'provisoire')
                                                <form action="{{ route('admin.devis.finaliser', $devi) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Finaliser ce devis ? Il ne pourra plus être modifié.');">
                                                    @csrf @method('PATCH')
                                                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg"
                                                        title="Finaliser">
                                                        <span class="material-icons text-xl">check_circle</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        {{-- Accepter --}}
                                        @can('devis.update')
                                            @if ($devi->type === 'final' && $devi->statut !== 'accepte')
                                                <form action="{{ route('admin.devis.accept', $devi) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Marquer ce devis comme accepté ?');">
                                                    @csrf
                                                    <button class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg"
                                                        title="Accepter">
                                                        <span class="material-icons text-xl">thumb_up</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        {{-- Convertir en facture --}}
                                        @can('devis.convert')
                                            @if ($devi->statut === 'accepte' && !$devi->facture_id)
                                                <form action="{{ route('admin.devis.convert', $devi) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Convertir en facture ?');">
                                                    @csrf
                                                    <button class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg"
                                                        title="Convertir en facture">
                                                        <span class="material-icons text-xl">receipt_long</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        {{-- PDF --}}
                                        <a href="{{ route('admin.devis.pdf', $devi) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Télécharger PDF">
                                            <span class="material-icons text-xl">picture_as_pdf</span>
                                        </a>
                                        {{-- Facture --}}
                                        <a href="{{ route('admin.devis.pdf-sans-cachet', $devi) }}"
                                            class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg" title="PDF sans cachet">
                                            <span class="material-icons text-xl">receipt</span>
                                        </a>
                                        {{-- Supprimer --}}
                                        @can('devis.delete')
                                            @if ($devi->statut === 'brouillon')
                                                <form action="{{ route('admin.devis.destroy', $devi) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Supprimer ce devis ?');">
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">Aucun devis trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if ($devis->hasPages())
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">{{ $devis->firstItem() }} à {{ $devis->lastItem() }} sur
                            {{ $devis->total() }}</p>
                        {{ $devis->links() }}
                    </div>
                @endif
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @forelse($devis as $devi)
                    <div
                        class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $devi->type === 'final' ? 'border-green-500' : 'border-orange-500' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-bold text-gray-800">{{ $devi->numero }}</p>
                                <p class="text-xs text-gray-500">{{ $devi->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium {{ $devi->type === 'final' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} rounded-full">
                                {{ ucfirst($devi->type) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <p class="font-medium text-gray-800">{{ $devi->client->nom_complet }}</p>
                            <p class="text-sm text-gray-500">{{ $devi->client->email }}</p>
                        </div>
                        <div class="flex justify-between mb-3">
                            <div>
                                <p class="text-xs text-gray-500">Montant TTC</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ number_format($devi->total_ttc, 0, ',', ' ') }} {{ $devi->devise }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-3 border-t flex-wrap">
                            <a href="{{ route('admin.devis.show', $devi) }}"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                                <span class="material-icons text-lg">visibility</span><span>Voir</span>
                            </a>
                            @can('devis.update')
                                @if ($devi->type === 'provisoire' && !in_array($devi->statut, ['accepte', 'refuse', 'expire']))
                                    <a href="{{ route('admin.devis.edit', $devi) }}"
                                        class="flex items-center justify-center gap-1 px-3 py-2 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium">
                                        <span class="material-icons text-lg">edit</span>
                                    </a>
                                @endif
                            @endcan
                            @can('devis.send')
                                <a href="{{ route('admin.devis.show', $devi) }}?send=1"
                                    class="flex items-center justify-center gap-1 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-medium"
                                    title="Envoyer par email">
                                    <span class="material-icons text-lg">send</span>
                                </a>
                            @endcan
                            @can('devis.update')
                                @if ($devi->type === 'provisoire')
                                    <form action="{{ route('admin.devis.finaliser', $devi) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Finaliser ?');">
                                        @csrf @method('PATCH')
                                        <button
                                            class="flex items-center justify-center gap-1 px-3 py-2 bg-green-50 text-green-600 rounded-lg text-sm font-medium">
                                            <span class="material-icons text-lg">check_circle</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                            @can('devis.update')
                                @if ($devi->type === 'final' && $devi->statut !== 'accepte')
                                    <form action="{{ route('admin.devis.accept', $devi) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Accepter ?');">
                                        @csrf
                                        <button
                                            class="flex items-center justify-center gap-1 px-3 py-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-medium">
                                            <span class="material-icons text-lg">thumb_up</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                            @can('devis.convert')
                                @if ($devi->statut === 'accepte' && !$devi->facture_id)
                                    <form action="{{ route('admin.devis.convert', $devi) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Convertir ?');">
                                        @csrf
                                        <button
                                            class="flex items-center justify-center gap-1 px-3 py-2 bg-purple-50 text-purple-600 rounded-lg text-sm font-medium">
                                            <span class="material-icons text-lg">receipt_long</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                            <a href="{{ route('admin.devis.pdf', $devi) }}"
                                class="flex items-center justify-center px-3 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium">
                                <span class="material-icons text-lg">picture_as_pdf</span>
                            </a>
                            <a href="{{ route('admin.devis.pdf-sans-cachet', $devi) }}"
                                class="flex items-center justify-center px-3 py-2 bg-orange-50 text-orange-600 rounded-lg text-sm font-medium">
                                <span class="material-icons text-lg">receipt</span>
                            </a>
                            @can('devis.delete')
                                @if ($devi->statut === 'brouillon')
                                    <form action="{{ route('admin.devis.destroy', $devi) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ?');">
                                        @csrf @method('DELETE')
                                        <button
                                            class="flex items-center justify-center px-3 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium">
                                            <span class="material-icons text-lg">delete</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-500">Aucun devis</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
