@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="history.back()" class="md:hidden text-gray-600">
                        <span class="material-icons">arrow_back</span>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Projets / Réalisations</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez les projets affichés sur le site</p>
                    </div>
                </div>
                @can('projets.create')
                    <a href="{{ route('admin.projets.create') }}"
                        class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouveau projet</span>
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
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-purple-600">photo_library</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Projet::count() }}</p>
                            <p class="text-xs text-gray-500">Total projets</p>
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
                                {{ \App\Models\Projet::where('actif', true)->count() }}</p>
                            <p class="text-xs text-gray-500">Actifs</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" class="flex flex-col md:flex-row gap-3 mb-6">
                <div class="relative flex-1">
                    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un projet..."
                        class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <select name="categorie" onchange="this.form.submit()"
                    class="px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                    <option value="">Toutes catégories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Table Desktop -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Projet</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Année</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($projets as $projet)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    @if ($projet->image_path)
                                        <img src="{{ asset('storage/' . $projet->image_path) }}"
                                            class="w-14 h-10 rounded-lg object-cover" alt="">
                                    @else
                                        <div class="w-14 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <span class="material-icons text-gray-400">image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $projet->titre }}</p>
                                    <p class="text-xs text-gray-500">{{ $projet->lieu }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $projet->client_nom ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($projet->categorie)
                                        <span
                                            class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">{{ ucfirst($projet->categorie) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $projet->annee ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $projet->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $projet->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('projets.update')
                                            <a href="{{ route('admin.projets.edit', $projet) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                                <span class="material-icons text-xl">edit</span>
                                            </a>
                                        @endcan
                                        @can('projets.delete')
                                            <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST"
                                                onsubmit="return confirm('Supprimer ce projet ?')">
                                                @csrf @method('DELETE')
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                                    <span class="material-icons text-xl">delete</span>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <span class="material-icons text-5xl mb-2">photo_library</span>
                                    <p class="font-medium">Aucun projet</p>
                                    <p class="text-sm">Ajoutez vos réalisations pour les afficher sur le site</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-3">
                @forelse($projets as $projet)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        @if ($projet->image_path)
                            <img src="{{ asset('storage/' . $projet->image_path) }}" class="w-full h-40 object-cover"
                                alt="">
                        @endif
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $projet->titre }}</p>
                                    <p class="text-xs text-gray-500">{{ $projet->client_nom }} · {{ $projet->lieu }} ·
                                        {{ $projet->annee }}</p>
                                </div>
                                <div class="flex gap-1">
                                    @can('projets.update')
                                        <a href="{{ route('admin.projets.edit', $projet) }}" class="p-2 text-blue-600">
                                            <span class="material-icons">edit</span>
                                        </a>
                                    @endcan
                                    @can('projets.delete')
                                        <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-red-600"><span
                                                    class="material-icons">delete</span></button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <span class="material-icons text-5xl">photo_library</span>
                        <p class="mt-2">Aucun projet</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $projets->links() }}</div>
        </div>
    </div>
@endsection
