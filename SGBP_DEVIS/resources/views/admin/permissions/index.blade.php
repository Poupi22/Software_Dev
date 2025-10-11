@extends('admin.layouts.app')

@section('title', 'Gestion des Permissions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Permissions</h1>
            <p class="text-gray-600">Gérez les permissions du système</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
            @can('permissions.create')
                <button onclick="window.location.href='{{ route('admin.permissions.sync') }}'"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <span class="material-icons text-sm mr-2">sync</span>
                    Synchroniser
                </button>
                <button onclick="window.location.href='{{ route('admin.permissions.create') }}'"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium rounded-lg transition-all shadow-lg">
                    <span class="material-icons text-sm mr-2">add</span>
                    Nouvelle Permission
                </button>
            @endcan
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <span class="material-icons text-green-500 mr-3">check_circle</span>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <span class="material-icons text-red-500 mr-3">error</span>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-center">
                <span class="material-icons text-blue-500 mr-3">info</span>
                <p class="text-blue-700">{{ session('info') }}</p>
            </div>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <div class="relative">
                    <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher une permission..."
                           class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filtre par groupe -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Groupe</label>
                <select name="group"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Tous les groupes</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') === $group ? 'selected' : '' }}>
                            {{ ucfirst($group) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Boutons -->
            <div class="md:col-span-3 flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    Filtrer
                </button>
                <a href="{{ route('admin.permissions.index') }}"
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Permissions</p>
                    <p class="text-3xl font-bold">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <span class="material-icons text-4xl">lock</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Groupes</p>
                    <p class="text-3xl font-bold">{{ $groups->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <span class="material-icons text-4xl">category</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">En cours de filtre</p>
                    <p class="text-3xl font-bold">{{ $permissions->total() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <span class="material-icons text-4xl">filter_alt</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des permissions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permission
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Groupe
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Guard
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôles
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        @canany(['permissions.update', 'permissions.delete'])
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($permissions as $permission)
                        @php
                            $parts = explode('.', $permission->name);
                            $group = $parts[0] ?? 'other';
                            $action = $parts[1] ?? $permission->name;
                            $rolesCount = $permission->roles()->count();
                            $isCritical = in_array($permission->name, [
                                'users.viewAny', 'users.create', 'users.update', 'users.delete',
                                'roles.viewAny', 'roles.create', 'roles.update', 'roles.delete',
                                'permissions.viewAny', 'permissions.create', 'permissions.update', 'permissions.delete'
                            ]);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="material-icons text-purple-500 mr-3">{{ $isCritical ? 'shield' : 'lock_outline' }}</span>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($action) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($group) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rolesCount > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="material-icons text-xs mr-1">group</span>
                                        {{ $rolesCount }} rôle(s)
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Aucun</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isCritical)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="material-icons text-xs mr-1">warning</span>
                                        Critique
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Standard
                                    </span>
                                @endif
                            </td>
                            @canany(['permissions.update', 'permissions.delete'])
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('permissions.manage)
                                            <a href="{{ route('admin.permissions.show', $permission) }}"
                                               class="text-blue-600 hover:text-blue-900 transition-colors"
                                               title="Voir les détails">
                                                <span class="material-icons text-xl">visibility</span>
                                            </a>
                                        @endcan

                                        @can('permissions.update')
                                            @if(!$isCritical)
                                                <a href="{{ route('admin.permissions.edit', $permission) }}"
                                                   class="text-purple-600 hover:text-purple-900 transition-colors"
                                                   title="Modifier">
                                                    <span class="material-icons text-xl">edit</span>
                                                </a>
                                            @else
                                                <span class="text-gray-300 cursor-not-allowed" title="Permission critique non modifiable">
                                                    <span class="material-icons text-xl">edit_off</span>
                                                </span>
                                            @endif
                                        @endcan

                                        @can('permissions.delete')
                                            @if(!$isCritical && $rolesCount === 0)
                                                <form action="{{ route('admin.permissions.destroy', $permission) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette permission ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 transition-colors"
                                                            title="Supprimer">
                                                        <span class="material-icons text-xl">delete</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 cursor-not-allowed"
                                                      title="{{ $isCritical ? 'Permission critique non supprimable' : 'Permission utilisée par des rôles' }}">
                                                    <span class="material-icons text-xl">delete_off</span>
                                                </span>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-icons text-6xl text-gray-300 mb-4">search_off</span>
                                <p class="text-gray-500 text-lg">Aucune permission trouvée</p>
                                @if(request('search') || request('group'))
                                    <a href="{{ route('admin.permissions.index') }}"
                                       class="inline-block mt-4 text-purple-600 hover:text-purple-800 font-medium">
                                        Réinitialiser les filtres
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($permissions->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
