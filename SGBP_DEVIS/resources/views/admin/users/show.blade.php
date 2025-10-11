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
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($user->prenom, 0, 1) . substr($user->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                                {{ $user->nom_complet }}
                                @if ($user->isSuperAdmin())
                                    <span class="material-icons text-yellow-500 text-xl" title="Super Admin">shield</span>
                                @endif
                            </h2>
                            <p class="text-xs md:text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($user->is_active)
                        <span
                            class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span>Actif</span>
                        </span>
                    @else
                        <span
                            class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 text-red-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            <span>Inactif</span>
                        </span>
                    @endif
                    @can('users.update')
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <span class="material-icons text-xl">edit</span>
                            <span class="hidden md:inline">Modifier</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Colonne principale -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Informations personnelles -->
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-blue-600">person</span>
                                Informations personnelles
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Prénom</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-900">{{ $user->prenom }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Nom</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-900">{{ $user->nom }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Email</span>
                                    <a href="mailto:{{ $user->email }}"
                                        class="col-span-2 text-sm text-blue-600 hover:text-blue-800">{{ $user->email }}</a>
                                </div>
                                <div class="grid grid-cols-3 gap-4 py-3">
                                    <span class="text-sm font-medium text-gray-500">Téléphone</span>
                                    @if ($user->telephone)
                                        <a href="tel:{{ $user->telephone }}"
                                            class="col-span-2 text-sm text-blue-600 hover:text-blue-800">{{ $user->telephone }}</a>
                                    @else
                                        <span class="col-span-2 text-sm text-gray-400">Non renseigné</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Rôles et permissions -->
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-blue-600">admin_panel_settings</span>
                                Rôles et permissions
                            </h3>

                            <!-- Rôles -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Rôles assignés</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <div
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-800 rounded-lg border border-purple-200">
                                            <span class="material-icons text-lg">shield</span>
                                            <span class="font-medium">{{ $role->name }}</span>
                                            <span class="text-xs text-purple-600">({{ $role->permissions()->count() }}
                                                permissions)</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">Aucun rôle assigné</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Permissions -->
                            @if ($user->roles->isNotEmpty())
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Permissions (via rôles)</h4>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-96 overflow-y-auto">
                                        @php
                                            $allPermissions = $user
                                                ->getAllPermissions()
                                                ->groupBy(function ($permission) {
                                                    $parts = explode('.', $permission->name);
                                                    return $parts[0] ?? 'other';
                                                });
                                        @endphp
                                        @foreach ($allPermissions as $group => $permissions)
                                            <div class="mb-4 last:mb-0">
                                                <h5
                                                    class="text-xs font-semibold text-gray-600 uppercase mb-2 flex items-center gap-2">
                                                    <span class="material-icons text-sm">folder</span>
                                                    {{ ucfirst($group) }}
                                                </h5>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                    @foreach ($permissions as $permission)
                                                        <div
                                                            class="flex items-center gap-2 text-xs text-gray-700 bg-white px-3 py-2 rounded border border-gray-200">
                                                            <span
                                                                class="material-icons text-sm text-green-600">check_circle</span>
                                                            <span>{{ $permission->name }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Colonne latérale -->
                    <div class="space-y-6">
                        <!-- Statistiques -->
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-sm">analytics</span>
                                Statistiques
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <span class="material-icons text-purple-600">shield</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Rôles</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $user->roles->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <span class="material-icons text-blue-600">lock</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">Permissions</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                {{ $user->getAllPermissions()->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du compte -->
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-sm">info</span>
                                Informations du compte
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Statut :</span>
                                    @if ($user->is_active)
                                        <span class="font-medium text-green-600">Actif</span>
                                    @else
                                        <span class="font-medium text-red-600">Inactif</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Créé le :</span>
                                    <span class="font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Modifié le :</span>
                                    <span class="font-medium text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">ID :</span>
                                    <span class="font-mono text-xs text-gray-900">{{ $user->id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-sm">bolt</span>
                                Actions rapides
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-blue-600 flex items-center gap-2">
                                        <span class="material-icons text-sm">list</span>
                                        Tous les utilisateurs
                                    </span>
                                    <span
                                        class="material-icons text-gray-400 group-hover:text-blue-600">arrow_forward</span>
                                </a>

                                @can('users.update')
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                        <span
                                            class="text-sm font-medium text-gray-700 group-hover:text-blue-600 flex items-center gap-2">
                                            <span class="material-icons text-sm">edit</span>
                                            Modifier
                                        </span>
                                        <span
                                            class="material-icons text-gray-400 group-hover:text-blue-600">arrow_forward</span>
                                    </a>
                                @endcan

                                @can('roles.manage')
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                        <span
                                            class="text-sm font-medium text-gray-700 group-hover:text-blue-600 flex items-center gap-2">
                                            <span class="material-icons text-sm">shield</span>
                                            Gérer les rôles
                                        </span>
                                        <span
                                            class="material-icons text-gray-400 group-hover:text-blue-600">arrow_forward</span>
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
