@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="history.back()" class="md:hidden text-gray-600">
                        <span class="material-icons">arrow_back</span>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Services</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez les services affichés sur le site</p>
                    </div>
                </div>
                @can('services.create')
                    <a href="{{ route('admin.services.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouveau service</span>
                    </a>
                @endcan
            </div>
        </div>

        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>{{ session('success') }}
                </p>
            </div>
        @endif

        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-blue-600">build</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Service::count() }}</p>
                            <p class="text-xs text-gray-500">Total services</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-green-600">visibility</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \App\Models\Service::where('actif', true)->count() }}</p>
                            <p class="text-xs text-gray-500">Actifs</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <form method="GET" class="mb-6">
                <div class="relative">
                    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un service..."
                        class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
            </form>

            <!-- Table Desktop -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ordre</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $service->ordre }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($service->image_path)
                                            <img src="{{ asset('storage/' . $service->image_path) }}"
                                                class="w-10 h-10 rounded-lg object-cover" alt="">
                                        @elseif($service->icon)
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="material-icons text-blue-600">{{ $service->icon }}</span>
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $service->nom }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($service->description, 60) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $service->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $service->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('services.update')
                                            <a href="{{ route('admin.services.edit', $service) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Modifier">
                                                <span class="material-icons text-xl">edit</span>
                                            </a>
                                        @endcan
                                        @can('services.delete')
                                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                                onsubmit="return confirm('Supprimer ce service ?')">
                                                @csrf @method('DELETE')
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Supprimer">
                                                    <span class="material-icons text-xl">delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <span class="material-icons text-5xl mb-2">build</span>
                                    <p class="font-medium">Aucun service</p>
                                    <p class="text-sm">Créez votre premier service pour l'afficher sur le site</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-3">
                @forelse($services as $service)
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                @if ($service->image_path)
                                    <img src="{{ asset('storage/' . $service->image_path) }}"
                                        class="w-10 h-10 rounded-lg object-cover" alt="">
                                @elseif($service->icon)
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="material-icons text-blue-600">{{ $service->icon }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $service->nom }}</p>
                                    <span class="text-xs {{ $service->actif ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $service->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @can('services.update')
                                    <a href="{{ route('admin.services.edit', $service) }}" class="p-2 text-blue-600">
                                        <span class="material-icons">edit</span>
                                    </a>
                                @endcan
                                @can('services.delete')
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-red-600"><span class="material-icons">delete</span></button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">{{ Str::limit($service->description, 80) }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <span class="material-icons text-5xl">build</span>
                        <p class="mt-2">Aucun service</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $services->links() }}</div>
        </div>
    </div>
@endsection
