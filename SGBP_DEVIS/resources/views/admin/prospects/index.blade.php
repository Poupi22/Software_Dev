@extends('admin.layouts.app')
@section('title', 'Liste des Prospects')
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
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Prospects</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez vos demandes de contact</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.prospects.index') }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                        title="Rafraîchir">
                        <span class="material-icons">refresh</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Messages flash --}}
        @if (session('success'))
            <div class="mx-4 md:mx-8 mt-4">
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <span class="material-icons text-green-600">check_circle</span>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-blue-600">person_add</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                            <p class="text-xs text-gray-500">Total prospects</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-orange-600">new_releases</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['nouveaux'] }}</p>
                            <p class="text-xs text-gray-500">Nouveaux</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-green-600">check_circle</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['convertis'] }}</p>
                            <p class="text-xs text-gray-500">Convertis</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-purple-600">trending_up</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['taux_conversion'] }}%</p>
                            <p class="text-xs text-gray-500">Taux conversion</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form action="{{ route('admin.prospects.index') }}" method="GET" class="space-y-4">
                    <!-- Search -->
                    <div class="relative">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par nom, email, sujet..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Filter Chips -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-2">
                        <a href="{{ route('admin.prospects.index', request()->except('statut', 'page')) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ !request('statut') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Tous ({{ $stats['total'] }})
                        </a>
                        @foreach ([
            'nouveau' => ['label' => 'Nouveaux', 'count' => $stats['nouveaux']],
            'contacte' => ['label' => 'Contactés', 'count' => $stats['contactes']],
            'qualifie' => ['label' => 'Qualifiés', 'count' => $stats['qualifies']],
            'converti' => ['label' => 'Convertis', 'count' => $stats['convertis']],
            'perdu' => ['label' => 'Perdus', 'count' => $stats['perdus']],
        ] as $statut => $info)
                            <a href="{{ route('admin.prospects.index', array_merge(request()->except('page'), ['statut' => $statut])) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('statut') === $statut ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $info['label'] }} ({{ $info['count'] }})
                            </a>
                        @endforeach
                    </div>

                    <!-- Sort -->
                    <div class="flex items-center gap-3">
                        <select name="sort" onchange="this.form.submit()"
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Trier par
                                : Date (récent)</option>
                            <option value="ancien" {{ request('sort') === 'ancien' ? 'selected' : '' }}>Date (ancien)
                            </option>
                            <option value="nom" {{ request('sort') === 'nom' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="statut" {{ request('sort') === 'statut' ? 'selected' : '' }}>Statut</option>
                        </select>
                    </div>
                </form>
            </div>

            @php
                $statutColors = [
                    'nouveau' => 'bg-orange-100 text-orange-700',
                    'contacte' => 'bg-blue-100 text-blue-700',
                    'qualifie' => 'bg-purple-100 text-purple-700',
                    'converti' => 'bg-green-100 text-green-700',
                    'perdu' => 'bg-red-100 text-red-700',
                ];
                $statutLabels = [
                    'nouveau' => 'Nouveau',
                    'contacte' => 'Contacté',
                    'qualifie' => 'Qualifié',
                    'converti' => 'Converti',
                    'perdu' => 'Perdu',
                ];
                $borderColors = [
                    'nouveau' => 'border-orange-500',
                    'contacte' => 'border-blue-500',
                    'qualifie' => 'border-purple-500',
                    'converti' => 'border-green-500',
                    'perdu' => 'border-red-500',
                ];
            @endphp

            <!-- Prospects List (Desktop) -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Prospect</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Sujet</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Source</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($prospects as $prospect)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $initials = strtoupper(
                                                substr($prospect->nom, 0, 1) . substr($prospect->prenom ?? '', 0, 1),
                                            );
                                            $avatarColors = [
                                                'bg-blue-100 text-blue-600',
                                                'bg-green-100 text-green-600',
                                                'bg-purple-100 text-purple-600',
                                                'bg-orange-100 text-orange-600',
                                                'bg-red-100 text-red-600',
                                            ];
                                            $avatarColor = $avatarColors[$prospect->id % 5];
                                        @endphp
                                        <div
                                            class="w-10 h-10 {{ $avatarColor }} rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ $initials ?: '?' }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $prospect->nom_complet }}</p>
                                            <p class="text-sm text-gray-500">{{ $prospect->email }}</p>
                                            @if ($prospect->entreprise)
                                                <p class="text-xs text-gray-400">{{ $prospect->entreprise }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800 text-sm">{{ $prospect->objet ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($prospect->message, 40) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($prospect->source === 'site_web')
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Site
                                            web</span>
                                    @elseif($prospect->source === 'manuel')
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">Manuel</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">{{ ucfirst($prospect->source ?? 'N/A') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $prospect->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $prospect->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-medium rounded-full {{ $statutColors[$prospect->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statutLabels[$prospect->statut] ?? ucfirst($prospect->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.prospects.show', $prospect) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Voir le détail">
                                            <span class="material-icons text-xl">visibility</span>
                                        </a>
                                        @if ($prospect->email)
                                            <a href="mailto:{{ $prospect->email }}"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg"
                                                title="Répondre par email">
                                                <span class="material-icons text-xl">reply</span>
                                            </a>
                                        @endif
                                        @if ($prospect->telephone)
                                            <a href="tel:{{ $prospect->telephone }}"
                                                class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg" title="Appeler">
                                                <span class="material-icons text-xl">phone</span>
                                            </a>
                                        @endif
                                        @if ($prospect->statut !== 'converti')
                                            @can('prospects.convert')
                                                <form action="{{ route('admin.prospects.convert', $prospect) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Convertir ce prospect en client ?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg"
                                                        title="Convertir en client">
                                                        <span class="material-icons text-xl">person_add</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            @if ($prospect->client)
                                                <a href="{{ route('admin.clients.show', $prospect->client) }}"
                                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg"
                                                    title="Voir le client">
                                                    <span class="material-icons text-xl">open_in_new</span>
                                                </a>
                                            @endif
                                        @endif
                                        @can('prospects.delete')
                                            @if ($prospect->statut !== 'converti')
                                                <form action="{{ route('admin.prospects.destroy', $prospect) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Supprimer ce prospect ?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <span class="material-icons text-gray-300 text-6xl mb-3">person_add</span>
                                    <p class="text-gray-500 text-lg">Aucun prospect trouvé</p>
                                    <p class="text-gray-400 text-sm mt-1">Les demandes de contact apparaîtront ici</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if ($prospects->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $prospects->links() }}
                    </div>
                @else
                    <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-600">
                        {{ $prospects->total() }} prospect{{ $prospects->total() > 1 ? 's' : '' }} au total
                    </div>
                @endif
            </div>

            <!-- Prospects List (Mobile Cards) -->
            <div class="md:hidden space-y-4">
                @forelse($prospects as $prospect)
                    <div
                        class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $borderColors[$prospect->statut] ?? 'border-gray-500' }}">
                        <div class="flex items-start gap-3 mb-3">
                            @php
                                $initials = strtoupper(
                                    substr($prospect->nom, 0, 1) . substr($prospect->prenom ?? '', 0, 1),
                                );
                                $avatarColors = [
                                    'bg-blue-100 text-blue-600',
                                    'bg-green-100 text-green-600',
                                    'bg-purple-100 text-purple-600',
                                    'bg-orange-100 text-orange-600',
                                    'bg-red-100 text-red-600',
                                ];
                                $avatarColor = $avatarColors[$prospect->id % 5];
                            @endphp
                            <div
                                class="w-12 h-12 {{ $avatarColor }} rounded-full flex items-center justify-center font-bold flex-shrink-0">
                                {{ $initials ?: '?' }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-1">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $prospect->nom_complet }}</h3>
                                        @if ($prospect->entreprise)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">{{ $prospect->entreprise }}</span>
                                        @endif
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap {{ $statutColors[$prospect->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statutLabels[$prospect->statut] ?? ucfirst($prospect->statut) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2 mb-3">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="material-icons text-lg">email</span>
                                <span>{{ $prospect->email }}</span>
                            </div>
                            @if ($prospect->telephone)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="material-icons text-lg">phone</span>
                                    <span>{{ $prospect->telephone }}</span>
                                </div>
                            @endif
                            @if ($prospect->objet || $prospect->message)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    @if ($prospect->objet)
                                        <p class="text-xs text-gray-500 mb-1">Sujet</p>
                                        <p class="font-medium text-gray-800 text-sm">{{ $prospect->objet }}</p>
                                    @endif
                                    <p class="text-xs text-gray-600">{{ Str::limit($prospect->message, 60) }}</p>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500">{{ $prospect->created_at->format('d/m/Y à H:i') }}</p>
                                @if ($prospect->source === 'site_web')
                                    <span class="text-xs text-blue-600">via site web</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.prospects.show', $prospect) }}"
                                class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                                <span class="material-icons text-lg">visibility</span>
                                <span>Voir</span>
                            </a>
                            @if ($prospect->email)
                                <a href="mailto:{{ $prospect->email }}"
                                    class="flex items-center justify-center w-10 h-10 text-green-600 hover:bg-green-50 rounded-lg">
                                    <span class="material-icons">reply</span>
                                </a>
                            @endif
                            @if ($prospect->statut !== 'converti')
                                @can('prospects.convert')
                                    <form action="{{ route('admin.prospects.convert', $prospect) }}" method="POST"
                                        onsubmit="return confirm('Convertir ?')">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center justify-center w-10 h-10 text-purple-600 hover:bg-purple-50 rounded-lg">
                                            <span class="material-icons">person_add</span>
                                        </button>
                                    </form>
                                @endcan
                                @can('prospects.delete')
                                    <form action="{{ route('admin.prospects.destroy', $prospect) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce prospect ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center justify-center w-10 h-10 text-red-500 hover:bg-red-50 rounded-lg">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </form>
                                @endcan
                            @else
                                @if ($prospect->client)
                                    <a href="{{ route('admin.clients.show', $prospect->client) }}"
                                        class="flex items-center justify-center w-10 h-10 text-purple-600 hover:bg-purple-50 rounded-lg">
                                        <span class="material-icons">open_in_new</span>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="material-icons text-gray-300 text-6xl mb-3">person_add</span>
                        <p class="text-gray-500 text-lg">Aucun prospect</p>
                    </div>
                @endforelse

                @if ($prospects->hasPages())
                    <div class="mt-4">
                        {{ $prospects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
