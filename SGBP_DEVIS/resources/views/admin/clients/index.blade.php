@extends('admin.layouts.app')
@section('content')

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="history.back()" class="md:hidden text-gray-600">
                        <span class="material-icons">arrow_back</span>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Clients</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez vos clients et prospects</p>
                    </div>
                </div>
                @can('clients.create')
                    <a href="{{ route('admin.clients.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">person_add</span>
                        <span class="hidden md:inline font-medium">Nouveau client</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-blue-600">people</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Client::count() }}</p>
                            <p class="text-xs text-gray-500">Total clients</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-purple-600">business</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\Client::where('type', 'societe')->count() }}</p>
                            <p class="text-xs text-gray-500">Sociétés</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-green-600">person</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\Client::where('type', 'particulier')->count() }}</p>
                            <p class="text-xs text-gray-500">Particuliers</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-orange-600">person_add</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\Client::whereMonth('created_at', now()->month)->count() }}</p>
                            <p class="text-xs text-gray-500">Ce mois</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.clients.index') }}">
                    <div class="relative mb-4">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par nom, email, téléphone, RCCM..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </form>

                <!-- Filter Tabs -->
                <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                    <a href="{{ route('admin.clients.index') }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ !request('type') && !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                        Tous ({{ \App\Models\Client::count() }})
                    </a>
                    <a href="{{ route('admin.clients.index', ['type' => 'societe']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'societe' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                        Sociétés ({{ \App\Models\Client::where('type', 'societe')->count() }})
                    </a>
                    <a href="{{ route('admin.clients.index', ['type' => 'particulier']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'particulier' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                        Particuliers ({{ \App\Models\Client::where('type', 'particulier')->count() }})
                    </a>
                    <a href="{{ route('admin.clients.index', ['status' => 'actif']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ request('status') === 'actif' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                        Actifs ({{ \App\Models\Client::where('actif', true)->count() }})
                    </a>
                </div>

                <!-- Sort -->
                <div class="flex items-center gap-3">
                    <select
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                        <option>Trier par : Nom (A-Z)</option>
                        <option>Nom (Z-A)</option>
                        <option>Date d'ajout (récent)</option>
                        <option>Date d'ajout (ancien)</option>
                        <option>CA (croissant)</option>
                        <option>CA (décroissant)</option>
                    </select>
                    <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <span class="material-icons">filter_list</span>
                    </button>
                </div>
            </div>

            <!-- Clients List (Desktop) -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Localisation</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Documents</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">CA Total</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 {{ $client->type === 'societe' ? 'bg-purple-600' : 'bg-green-600' }} rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($client->nom_complet, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $client->nom_complet }}</p>
                                            <p class="text-sm text-gray-500">Client depuis {{ $client->created_at->year }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium {{ $client->type === 'societe' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }} rounded-full">
                                        {{ $client->type_display }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-800">{{ $client->email ?? 'Non renseigné' }}</p>
                                    <p class="text-sm text-gray-500">{{ $client->telephone_principal }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-800">{{ $client->ville ?? '-' }}</p>
                                    <p class="text-sm text-gray-500">{{ $client->pays ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-center">
                                            <p class="font-bold text-blue-600">{{ $client->devis()->count() }}</p>
                                            <p class="text-xs text-gray-500">Devis</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-bold text-green-600">{{ $client->factures()->count() }}</p>
                                            <p class="text-xs text-gray-500">Factures</p>
                                        </div>

                                    </div>
                                </td>
                                {{-- <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">
                                        {{ number_format($client->factures()->sum('montant_ttc'), 0, ',', ' ') }} FCFA
                                    </p>
                                </td> --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">
                                        - FCFA
                                    </p>
                                    <p class="text-xs text-gray-500">Non disponible</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @can('clients.read')
                                            <a href="{{ route('admin.clients.show', $client) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                                <span class="material-icons text-xl">visibility</span>
                                            </a>
                                        @endcan
                                        @can('clients.update')
                                            <a href="{{ route('admin.clients.edit', $client) }}"
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                                <span class="material-icons text-xl">edit</span>
                                            </a>
                                        @endcan

                                        @can('clients.update')
                                            @if ($client->id !== 1)
                                                {{-- Ne pas permettre sur client ID 1 si c'est un client test --}}
                                                <form action="{{ route('admin.clients.toggle-status', $client) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="p-2 {{ $client->actif ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg"
                                                        title="{{ $client->actif ? 'Désactiver' : 'Activer' }}">
                                                        <span
                                                            class="material-icons text-xl">{{ $client->actif ? 'block' : 'check_circle' }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('clients.delete')
                                            <form action="{{ route('admin.clients.destroy', $client) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                                    <span class="material-icons text-xl">delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-gray-500">Aucun client trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Affichage de 1 à 10 sur 156 clients</p>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <span class="material-icons text-sm">chevron_left</span>
                        </button>
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">1</button>
                        <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">2</button>
                        <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">3</button>
                        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <span class="material-icons text-sm">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleMenu() {
            // Logic to toggle mobile menu
            alert('Toggle mobile menu');
        }

        function openCreateClient() {
            // Logic to open create client modal/form
            alert('Open create client form');
        }
    </script>
@endsection
