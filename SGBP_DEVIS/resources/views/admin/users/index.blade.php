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
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Utilisateurs</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez les accès et permissions</p>
                    </div>
                </div>
                @can('users.create')
                    <a href="{{ route('admin.users.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">person_add</span>
                        <span class="hidden md:inline font-medium">Nouvel utilisateur</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="mx-4 md:mx-8 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="material-icons text-green-500 mr-3">check_circle</span>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mx-4 md:mx-8 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <span class="material-icons text-red-500 mr-3">error</span>
                    <p class="text-red-700">{{ session('error') }}</p>
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
                            <span class="material-icons text-blue-600">people</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                            <p class="text-xs text-gray-500">Total utilisateurs</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-green-600">check_circle</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\User::where('actif', true)->count() }}</p>
                            <p class="text-xs text-gray-500">Actifs</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-purple-600">admin_panel_settings</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Spatie\Permission\Models\Role::whereHas('users')->count() }}</p>
                            <p class="text-xs text-gray-500">Rôles actifs</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-orange-600">block</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\User::where('actif', false)->count() }}</p>
                            <p class="text-xs text-gray-500">Inactifs</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <!-- Search -->
                    <div class="relative mb-4">
                        <span
                            class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par nom, email..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Filter Chips -->
                    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ !request('role') && !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Tous ({{ \App\Models\User::count() }})
                        </a>
                        @foreach ($roles as $role)
                            <a href="{{ route('admin.users.index', ['role' => $role->name]) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('role') === $role->name ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $role->name }} ({{ $role->users()->count() }})
                            </a>
                        @endforeach
                        <a href="{{ route('admin.users.index', ['status' => 'active']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'active' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Actifs ({{ \App\Models\User::where('actif', true)->count() }})
                        </a>
                        <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'inactive' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Inactifs ({{ \App\Models\User::where('actif', false)->count() }})
                        </a>
                    </div>

                    <!-- Sort -->
                    <select name="sort" onchange="this.form.submit()"
                        class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Trier
                            par : Date d'ajout (récent)</option>
                        <option value="created_at_asc" {{ request('sort') === 'created_at_asc' ? 'selected' : '' }}>Date
                            d'ajout (ancien)</option>
                        <option value="nom_asc" {{ request('sort') === 'nom_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="nom_desc" {{ request('sort') === 'nom_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                    </select>
                </form>
            </div>

            <!-- Users List (Desktop) -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Rôle(s)</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Création</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 {{ !$user->actif ? 'opacity-60' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 flex items-center gap-2">
                                                {{ $user->nom_complet }}
                                                @if ($user->isSuperAdmin())
                                                    <span class="material-icons text-yellow-500 text-sm"
                                                        title="Super Admin">shield</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span
                                                class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full flex items-center gap-1">
                                                <span class="material-icons text-xs">admin_panel_settings</span>
                                                <span>{{ $role->name }}</span>
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">Aucun rôle</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $user->email }}</p>
                                    @if ($user->telephone)
                                        <p class="text-sm text-gray-500">{{ $user->telephone }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $user->created_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->actif)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Actif</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @can('users.read')
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Voir">
                                                <span class="material-icons text-xl">visibility</span>
                                            </a>
                                        @endcan

                                        @can('users.update')
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Modifier">
                                                <span class="material-icons text-xl">edit</span>
                                            </a>
                                        @endcan

                                        @can('users.update')
                                            @if (!$user->isSuperAdmin() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="p-2 {{ $user->actif ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg"
                                                        title="{{ $user->actif ? 'Désactiver' : 'Activer' }}">
                                                        <span
                                                            class="material-icons text-xl">{{ $user->actif ? 'block' : 'check_circle' }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('users.delete')
                                            @if (!$user->isSuperAdmin() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
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
                                    <span class="material-icons text-6xl text-gray-300 mb-4 block">people_off</span>
                                    <p class="text-gray-500 text-lg">Aucun utilisateur trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Affichage de {{ $users->firstItem() }} à
                            {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs</p>
                        <div class="flex items-center gap-2">
                            @if ($users->onFirstPage())
                                <button class="px-3 py-1 border border-gray-300 rounded-lg opacity-50 cursor-not-allowed"
                                    disabled>
                                    <span class="material-icons text-sm">chevron_left</span>
                                </button>
                            @else
                                <a href="{{ $users->previousPageUrl() }}"
                                    class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <span class="material-icons text-sm">chevron_left</span>
                                </a>
                            @endif

                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @if ($page == $users->currentPage())
                                    <button
                                        class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}"
                                    class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <span class="material-icons text-sm">chevron_right</span>
                                </a>
                            @else
                                <button class="px-3 py-1 border border-gray-300 rounded-lg opacity-50 cursor-not-allowed"
                                    disabled>
                                    <span class="material-icons text-sm">chevron_right</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Users List (Mobile Cards) -->
            <div class="md:hidden space-y-4">
                @forelse($users as $user)
                    <div
                        class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $user->actif ? ($user->isSuperAdmin() ? 'border-purple-500' : 'border-green-500') : 'border-red-500 opacity-60' }}">
                        <div class="flex items-start gap-3 mb-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-1">
                                    <div>
                                        <h3 class="font-bold text-gray-800 flex items-center gap-1">
                                            {{ $user->nom_complet }}
                                            @if ($user->isSuperAdmin())
                                                <span class="material-icons text-yellow-500 text-sm">shield</span>
                                            @endif
                                        </h3>
                                        @forelse($user->roles as $role)
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-700 rounded-full inline-flex items-center gap-1">
                                                <span class="material-icons text-xs">admin_panel_settings</span>
                                                <span>{{ $role->name }}</span>
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">Aucun rôle</span>
                                        @endforelse
                                    </div>
                                    @if ($user->actif)
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full whitespace-nowrap">Actif</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full whitespace-nowrap">Inactif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 mb-3">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="material-icons text-lg">email</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            @if ($user->telephone)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="material-icons text-lg">phone</span>
                                    <span>{{ $user->telephone }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span class="material-icons text-lg">schedule</span>
                                <span>Créé le : {{ $user->created_at->format('d/m/Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            @can('users.update')
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                                    <span class="material-icons text-lg">edit</span>
                                    <span>Modifier</span>
                                </a>
                            @endcan

                            @can('users.read')
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="flex items-center justify-center w-10 h-10 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <span class="material-icons">visibility</span>
                                </a>
                            @endcan

                            @can('users.update')
                                @if (!$user->isSuperAdmin() && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="flex items-center justify-center w-10 h-10 {{ $user->actif ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg">
                                            <span class="material-icons">{{ $user->actif ? 'block' : 'check_circle' }}</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('users.delete')
                                @if (!$user->isSuperAdmin() && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center justify-center w-10 h-10 text-red-600 hover:bg-red-50 rounded-lg">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <span class="material-icons text-6xl text-gray-300 mb-4 block">people_off</span>
                        <p class="text-gray-500">Aucun utilisateur trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
