@extends('admin.layouts.app')
@section('content')
<div class="min-h-screen pb-20 bg-gray-50">
    <!-- Top bar -->
    <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Gestion des Rôles</h2>
                    <p class="text-xs md:text-sm text-gray-500">Créer et gérer les rôles utilisateurs</p>
                </div>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition shadow-md flex items-center gap-2">
                <span class="material-icons text-sm">add</span>
                <span class="hidden md:inline">Nouveau rôle</span>
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mx-4 md:mx-8 mt-4">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center gap-3">
            <span class="material-icons text-green-600">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-4 md:mx-8 mt-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-center gap-3">
            <span class="material-icons text-red-600">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Content -->
    <div class="p-4 md:p-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Rôles</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $roles->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-blue-600 text-2xl">badge</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Permissions Totales</p>
                            <p class="text-3xl font-bold text-gray-800">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-purple-600 text-2xl">verified_user</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Utilisateurs</p>
                            <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-green-600 text-2xl">people</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles List -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Rôle
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                    Permissions
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                    Utilisateurs
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                    Date création
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($roles as $role)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($role->name === 'super-admin')
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="material-icons text-white">shield</span>
                                        </div>
                                        @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="material-icons text-white">badge</span>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-gray-800">{{ ucfirst($role->name) }}</p>
                                            @if($role->name === 'super-admin')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                <span class="material-icons text-xs">lock</span>
                                                Protégé
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600 text-sm">verified_user</span>
                                        <span class="font-semibold text-gray-800">{{ $role->permissions->count() }}</span>
                                        <span class="text-sm text-gray-500">permissions</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <span class="material-icons text-green-600 text-sm">people</span>
                                        <span class="font-semibold text-gray-800">{{ $role->users->count() }}</span>
                                        <span class="text-sm text-gray-500">utilisateurs</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 hidden md:table-cell">
                                    {{ $role->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($role->name !== 'super-admin')
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Modifier">
                                            <span class="material-icons text-sm">edit</span>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                <span class="material-icons text-sm">delete</span>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-xs text-gray-400 italic px-3 py-1 bg-gray-100 rounded-lg">
                                            Non modifiable
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="material-icons text-gray-400 text-3xl">badge</span>
                                        </div>
                                        <p class="text-gray-500 font-medium">Aucun rôle trouvé</p>
                                        <a href="{{ route('admin.roles.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                            <span class="material-icons text-sm">add</span>
                                            Créer un rôle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection