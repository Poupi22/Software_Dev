@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Modifier Catégorie</h2>
                        <p class="text-xs md:text-sm text-gray-500">{{ $category->nom }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-medium {{ $category->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">
                    {{ $category->actif ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2">
                    <span class="material-icons">check_circle</span>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700 font-medium flex items-center gap-2">
                    <span class="material-icons">error</span>
                    Erreurs de validation :
                </p>
                <ul class="list-disc list-inside text-sm text-red-600 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Content -->
        <div class="p-4 md:p-8">
            <div class="max-w-3xl mx-auto">
                <!-- Infos supplémentaires -->
                @if($category->articles_count > 0)
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="material-icons text-blue-600">info</span>
                            <p class="text-sm text-blue-800">
                                Cette catégorie contient <strong>{{ $category->articles_count }} article(s)</strong>.
                            </p>
                        </div>
                    </div>
                @endif

                @include('admin.categories.form')
            </div>
        </div>
    </div>
@endsection