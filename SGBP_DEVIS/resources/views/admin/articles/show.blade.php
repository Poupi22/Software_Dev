SHOW.BLADE.PHP
@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.articles.index') }}"><span class="material-icons">arrow_back</span></a>
                    <div>
                        <h2 class="text-xl font-bold">{{ $article->nom }}</h2>
                        <p class="text-sm text-gray-500">{{ $article->type_display }}</p>
                    </div>
                </div>
                @can('articles.update')
                    <a href="{{ route('admin.articles.edit', $article) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg"><span class="material-icons">edit</span></a>
                @endcan
            </div>
        </div>
        <div class="p-4 md:p-8 max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Détails</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-4 py-3 border-b"><span
                                    class="text-sm text-gray-500">Nom</span><span
                                    class="col-span-2 font-medium">{{ $article->nom }}</span></div>
                            @if ($article->reference)
                                <div class="grid grid-cols-3 gap-4 py-3 border-b"><span
                                        class="text-sm text-gray-500">Référence</span><span
                                        class="col-span-2">{{ $article->reference }}</span></div>
                            @endif
                            <div class="grid grid-cols-3 gap-4 py-3 border-b"><span
                                    class="text-sm text-gray-500">Unité</span><span
                                    class="col-span-2">{{ $article->unite }}</span></div>
                            <div class="grid grid-cols-3 gap-4 py-3 border-b"><span class="text-sm text-gray-500">Prix
                                    HT</span><span
                                    class="col-span-2 font-bold">{{ number_format($article->prix_ht, 0, ',', ' ') }}
                                    FCFA</span></div>
                            <div class="grid grid-cols-3 gap-4 py-3"><span class="text-sm text-gray-500">Prix
                                    TTC</span><span
                                    class="col-span-2 font-bold">{{ number_format($article->prix_ttc, 0, ',', ' ') }}
                                    FCFA</span></div>
                        </div>
                    </div>
                    @if ($article->description)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold mb-3">Description</h3>
                            <p class="text-gray-700">{{ $article->description }}</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Catégories</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($article->categories as $cat)
                                <span class="px-3 py-1 text-sm bg-gray-100 rounded">{{ $cat->nom }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Informations</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600">Créé le
                                    :</span><span>{{ $article->created_at->format('d/m/Y') }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Modifié le
                                    :</span><span>{{ $article->updated_at->format('d/m/Y') }}</span></div>
                            @role('admin|superadmin')
                                @if ($article->createdBy)
                                    <div class="flex justify-between"><span class="text-gray-600">Créé par :</span><span
                                            class="font-medium">{{ $article->createdBy->nom_complet }}</span></div>
                                @endif
                                @if ($article->updatedBy)
                                    <div class="flex justify-between"><span class="text-gray-600">Modifié par :</span><span
                                            class="font-medium">{{ $article->updatedBy->nom_complet }}</span></div>
                                @endif
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
