@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                            style="background-color: {{ $category->couleur }}20;">
                            <span class="material-icons text-2xl"
                                style="color: {{ $category->couleur }}">{{ $category->icone }}</span>
                        </div>
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $category->nom }}</h2>
                            <p class="text-xs md:text-sm text-gray-500">{{ $category->articles_count }} article(s)</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="px-3 py-1 text-xs font-medium {{ $category->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">
                        {{ $category->actif ? 'Active' : 'Inactive' }}
                    </span>
                    @can('categories.update')
                        <a href="{{ route('admin.categories.edit', $category) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <span class="material-icons">edit</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 md:p-8 content-with-mobile-nav">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Colonne principale -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Description -->
                        @if ($category->description)
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <span class="material-icons text-blue-600">description</span>
                                    Description
                                </h3>
                                <p class="text-gray-700">{{ $category->description }}</p>
                            </div>
                        @endif

                        <!-- Articles de cette catégorie -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="material-icons text-blue-600">inventory_2</span>
                                Articles ({{ $category->articles->count() }})
                            </h3>

                            @if ($category->articles->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($category->articles as $article)
                                        <div
                                            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 {{ $article->type === 'produit' ? 'bg-green-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center">
                                                    <span
                                                        class="material-icons {{ $article->type === 'produit' ? 'text-green-600' : 'text-purple-600' }}">
                                                        {{ $article->type === 'produit' ? 'shopping_cart' : 'work' }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $article->nom }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ number_format($article->prix_ht, 0, ',', ' ') }} FCFA -
                                                        {{ $article->unite }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.articles.show', $article) }}"
                                                class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg">
                                                <span class="material-icons">arrow_forward</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">inventory_2</span>
                                    <p>Aucun article dans cette catégorie</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Colonne latérale -->
                    <div class="space-y-6">

                        <!-- Statistiques -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-blue-100">Total articles</span>
                                <span class="material-icons text-blue-200">inventory_2</span>
                            </div>
                            <p class="text-4xl font-bold">{{ $category->articles->count() }}</p>
                            <div class="mt-4 pt-4 border-t border-blue-400 grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-blue-100">Produits</p>
                                    <p class="font-bold text-xl">
                                        {{ $category->articles->where('type', 'produit')->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-100">Services</p>
                                    <p class="font-bold text-xl">
                                        {{ $category->articles->where('type', 'service')->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations système -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4">Informations</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Slug :</span>
                                    <span class="font-medium font-mono text-xs">{{ $category->slug }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ordre :</span>
                                    <span class="font-medium">{{ $category->ordre }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Créée le :</span>
                                    <span class="font-medium">{{ $category->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Modifiée le :</span>
                                    <span class="font-medium">{{ $category->updated_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-bold text-gray-800 mb-4">Actions rapides</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.categories.index') }}"
                                    class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <span class="material-icons text-sm">list</span>
                                        Toutes les catégories
                                    </span>
                                    <span class="material-icons text-gray-400">arrow_forward</span>
                                </a>
                                @can('categories.update')
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                        <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <span class="material-icons text-sm">edit</span>
                                            Modifier
                                        </span>
                                        <span class="material-icons text-gray-400">arrow_forward</span>
                                    </a>
                                @endcan
                                <a href="{{ route('admin.articles.index', ['category' => $category->id]) }}"
                                    class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                    <span class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                        <span class="material-icons text-sm">filter_alt</span>
                                        Filtrer les articles
                                    </span>
                                    <span class="material-icons text-gray-400">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
