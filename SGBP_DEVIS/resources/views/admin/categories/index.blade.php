@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">Catégories</h2>
                </div>
                @can('categories.create')
                    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg"><span
                            class="material-icons">add</span> Nouvelle</a>
                @endcan
            </div>
        </div>
        <div class="p-4 md:p-8 content-with-mobile-nav">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif
            <div class="grid md:grid-cols-3 gap-4">
                @foreach ($categories as $cat)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                    style="background-color: {{ $cat->couleur }}20;">
                                    <span class="material-icons"
                                        style="color: {{ $cat->couleur }}">{{ $cat->icone }}</span>
                                </div>
                                <h3 class="font-bold">{{ $cat->nom }}</h3>
                            </div>
                            <span
                                class="px-2 py-1 text-xs {{ $cat->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">{{ $cat->actif ? 'Actif' : 'Inactif' }}</span>
                        </div>
                        @if ($cat->description)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($cat->description, 80) }}</p>
                        @endif
                        <div class="flex items-center justify-between pt-4 border-t">
                            <span class="text-sm text-gray-500">{{ $cat->articles_count }} articles</span>
                            <div class="flex gap-2">
                                @can('categories.update')
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><span
                                            class="material-icons text-lg">edit</span></a>
                                    <form action="{{ route('admin.categories.toggle-status', $cat) }}" method="POST"
                                        class="inline">@csrf @method('PATCH')<button
                                            class="p-2 {{ $cat->actif ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg"><span
                                                class="material-icons text-lg">{{ $cat->actif ? 'block' : 'check_circle' }}</span></button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
