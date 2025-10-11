@extends('admin.layouts.app')

@section('title', 'Détails de la Permission')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.permissions.index') }}" class="hover:text-purple-600 transition-colors">Permissions</a>
            <span class="material-icons text-sm mx-2">chevron_right</span>
            <span class="text-gray-900 font-medium">{{ $permission->name }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Détails de la Permission</h1>
                <p class="text-gray-600">Informations complètes sur cette permission</p>
            </div>
            <div class="flex gap-3">
                @can('permissions.update')
                    @php
                        $isCritical = in_array($permission->name, [
                            'users.viewAny', 'users.create', 'users.update', 'users.delete',
                            'roles.viewAny', 'roles.create', 'roles.update', 'roles.delete',
                            'permissions.viewAny', 'permissions.create', 'permissions.update', 'permissions.delete'
                        ]);
                    @endphp
                    @if(!$isCritical)
                        <a href="{{ route('admin.permissions.edit', $permission) }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                            <span class="material-icons text-sm mr-2">edit</span>
                            Modifier
                        </a>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <span class="material-icons mr-2">lock</span>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Nom -->
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="text-sm font-medium text-gray-500">Nom complet</div>
                        <div class="col-span-2">
                            <code class="px-3 py-1.5 bg-gray-100 text-purple-700 rounded-lg font-mono text-sm">
                                {{ $permission->name }}
                            </code>
                        </div>
                    </div>

                    <!-- Groupe/Ressource -->
                    @php
                        $parts = explode('.', $permission->name);
                        $group = $parts[0] ?? 'other';
                        $action = $parts[1] ?? $permission->name;
                    @endphp
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="text-sm font-medium text-gray-500">Ressource</div>
                        <div class="col-span-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($group) }}
                            </span>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="text-sm font-medium text-gray-500">Action</div>
                        <div class="col-span-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ ucfirst($action) }}
                            </span>
                        </div>
                    </div>

                    <!-- Guard -->
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="text-sm font-medium text-gray-500">Guard</div>
                        <div class="col-span-2">
                            <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-gray-100 text-gray-700">
                                {{ $permission->guard_name }}
                            </span>
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="grid grid-cols-3 gap-4 py-3">
                        <div class="text-sm font-medium text-gray-500">Type</div>
                        <div class="col-span-2">
                            @php
                                $isCritical = in_array($permission->name, [
                                    'users.viewAny', 'users.create', 'users.update', 'users.delete',
                                    'roles.viewAny', 'roles.create', 'roles.update', 'roles.delete',
                                    'permissions.viewAny', 'permissions.create', 'permissions.update', 'permissions.delete'
                                ]);
                            @endphp
                            @if($isCritical)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <span class="material-icons text-xs mr-1">shield</span>
                                    Permission Critique
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Permission Standard
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôles utilisant cette permission -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <span class="material-icons mr-2">shield</span>
                        Rôles Associés ({{ $roles->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    @if($roles->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($roles as $role)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                                            <span class="material-icons text-white text-xl">shield</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $role->users()->count() }} utilisateur(s) •
                                                {{ $role->permissions()->count() }} permission(s)
                                            </p>
                                        </div>
                                    </div>
                                    @can('roles.update')
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                            <span class="material-icons text-sm mr-1">edit</span>
                                            Modifier
                                        </a>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <span class="material-icons text-6xl text-gray-300 mb-4">shield_off</span>
                            <p class="text-gray-500">Aucun rôle n'utilise cette permission</p>
                            <p class="text-sm text-gray-400 mt-2">
                                Cette permission peut être assignée à un rôle depuis la gestion des rôles
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <span class="material-icons text-sm mr-2">analytics</span>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Nombre de rôles -->
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="material-icons text-purple-600">shield</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Rôles</p>
                                <p class="text-xl font-bold text-gray-900">{{ $roles->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre d'utilisateurs -->
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="material-icons text-blue-600">people</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Utilisateurs (via rôles)</p>
                                <p class="text-xl font-bold text-gray-900">{{ $usersCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 flex items-center">
                        <span class="material-icons text-sm mr-2">bolt</span>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.permissions.index') }}"
                       class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                        <span class="text-sm font-medium text-gray-700 group-hover:text-purple-600 flex items-center">
                            <span class="material-icons text-sm mr-2">list</span>
                            Toutes les permissions
                        </span>
                        <span class="material-icons text-gray-400 group-hover:text-purple-600">arrow_forward</span>
                    </a>

                    @can('permissions.update')
                        @if(!$isCritical)
                            <a href="{{ route('admin.permissions.edit', $permission) }}"
                               class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-600 flex items-center">
                                    <span class="material-icons text-sm mr-2">edit</span>
                                    Modifier
                                </span>
                                <span class="material-icons text-gray-400 group-hover:text-purple-600">arrow_forward</span>
                            </a>
                        @endif
                    @endcan

                    @can('roles.manage)
                        <a href="{{ route('admin.roles.index') }}"
                           class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-600 flex items-center">
                                <span class="material-icons text-sm mr-2">shield</span>
                                Gérer les rôles
                            </span>
                            <span class="material-icons text-gray-400 group-hover:text-purple-600">arrow_forward</span>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Aide -->
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                    <span class="material-icons text-sm text-purple-600 mr-2">help</span>
                    Besoin d'aide ?
                </h3>
                <p class="text-xs text-gray-600 mb-4">
                    Les permissions contrôlent l'accès aux fonctionnalités du système. Elles sont attribuées via les rôles.
                </p>
                @if($isCritical)
                    <div class="bg-white bg-opacity-50 border border-purple-200 rounded-lg p-3">
                        <p class="text-xs text-gray-700">
                            <strong>Permission critique :</strong> Cette permission est essentielle et protégée contre la modification/suppression.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
