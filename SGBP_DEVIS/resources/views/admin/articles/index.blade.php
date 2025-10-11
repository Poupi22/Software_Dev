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
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Articles</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez votre catalogue produits et services</p>
                    </div>
                </div>
                @can('articles.create')
                    <a href="{{ route('admin.articles.create') }}" class="flex items-center gap-2 px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <span class="material-icons text-xl">add</span>
                        <span class="hidden md:inline font-medium">Nouvel article</span>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>{{ session('success') }}
                </p>
            </div>
        @endif

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-blue-600">inventory_2</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Article::count() }}</p>
                            <p class="text-xs text-gray-500">Total articles</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-green-600">shopping_cart</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Article::where('type', 'produit')->count() }}</p>
                            <p class="text-xs text-gray-500">Produits</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-purple-600">work</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Article::where('type', 'service')->count() }}</p>
                            <p class="text-xs text-gray-500">Services</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-orange-600">category</span>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Category::count() }}</p>
                            <p class="text-xs text-gray-500">Catégories</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-6">
                <form method="GET" action="{{ route('admin.articles.index') }}">
                    <!-- Search -->
                    <div class="relative mb-4">
                        <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un article..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Filter Chips -->
                    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
                        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->has('type') && !request()->has('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Tous ({{ \App\Models\Article::count() }})
                        </a>
                        <a href="{{ route('admin.articles.index', ['type' => 'produit']) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'produit' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Produits ({{ \App\Models\Article::where('type', 'produit')->count() }})
                        </a>
                        <a href="{{ route('admin.articles.index', ['type' => 'service']) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === 'service' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Services ({{ \App\Models\Article::where('type', 'service')->count() }})
                        </a>
                        <a href="{{ route('admin.articles.index', ['status' => 'actif']) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request('status') === 'actif' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} whitespace-nowrap">
                            Actifs ({{ \App\Models\Article::where('actif', true)->count() }})
                        </a>
                    </div>

                    <!-- Advanced Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <select name="category" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date ajout (récent)</option>
                            <option value="nom" {{ request('sort') === 'nom' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="prix_ht" {{ request('sort') === 'prix_ht' ? 'selected' : '' }}>Prix (croissant)</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Articles List (Desktop) -->
            <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Article</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Catégories</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Unité</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Prix HT</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($articles as $article)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 {{ $article->type === 'produit' ? 'bg-green-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center">
                                            <span class="material-icons {{ $article->type === 'produit' ? 'text-green-600' : 'text-purple-600' }}">
                                                {{ $article->type === 'produit' ? 'shopping_cart' : 'work' }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $article->nom }}</p>
                                            @if($article->reference)
                                                <p class="text-sm text-gray-500">Réf: {{ $article->reference }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-medium {{ $article->type === 'produit' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }} rounded-full">
                                        {{ $article->type_display }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($article->categories as $cat)
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $cat->nom }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $article->unite }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ number_format($article->prix_ht, 0, ',', ' ') }} FCFA</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-medium {{ $article->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">
                                        {{ $article->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @can('articles.update')
                                            <a href="{{ route('admin.articles.edit', $article) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                                <span class="material-icons text-xl">edit</span>
                                            </a>
                                            <form action="{{ route('admin.articles.toggle-status', $article) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 {{ $article->actif ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg">
                                                    <span class="material-icons text-xl">{{ $article->actif ? 'block' : 'check_circle' }}</span>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('articles.delete')
                                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet article ?');">
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">Aucun article trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Affichage de {{ $articles->firstItem() }} à {{ $articles->lastItem() }} sur {{ $articles->total() }}</p>
                        <div class="flex items-center gap-2">
                            @if(!$articles->onFirstPage())
                                <a href="{{ $articles->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <span class="material-icons text-sm">chevron_left</span>
                                </a>
                            @endif
                            @foreach($articles->getUrlRange(1, min(3, $articles->lastPage())) as $page => $url)
                                <a href="{{ $url }}" class="px-3 py-2 {{ $page == $articles->currentPage() ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50' }} rounded-lg text-sm">{{ $page }}</a>
                            @endforeach
                            @if($articles->hasMorePages())
                                <a href="{{ $articles->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <span class="material-icons text-sm">chevron_right</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4">
                @forelse($articles as $article)
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-12 h-12 {{ $article->type === 'produit' ? 'bg-green-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center">
                                <span class="material-icons {{ $article->type === 'produit' ? 'text-green-600' : 'text-purple-600' }}">
                                    {{ $article->type === 'produit' ? 'shopping_cart' : 'work' }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800">{{ $article->nom }}</h3>
                                <div class="flex gap-2 mt-1">
                                    <span class="px-2 py-0.5 text-xs font-medium {{ $article->type === 'produit' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }} rounded-full">
                                        {{ $article->type_display }}
                                    </span>
                                    @foreach($article->categories->take(1) as $cat)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $cat->nom }}</span>
                                    @endforeach
                                </div>
                                <p class="text-lg font-bold text-blue-600 mt-2">{{ number_format($article->prix_ht, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            @can('articles.update')
                                <a href="{{ route('admin.articles.edit', $article) }}" class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                                    <span class="material-icons text-lg">edit</span><span>Modifier</span>
                                </a>
                            @endcan
                            @can('articles.delete')
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                                    @csrf @method('DELETE')
                                    <button class="w-10 h-10 text-red-600 hover:bg-red-50 rounded-lg"><span class="material-icons">delete</span></button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-500">Aucun article</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection