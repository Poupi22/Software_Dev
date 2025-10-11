@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Nouvelle Catégorie</h2>
                    <p class="text-xs md:text-sm text-gray-500">Créer une catégorie d'articles</p>
                </div>
            </div>
        </div>

        <!-- Messages -->
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
                @include('admin.categories.form')
            </div>
        </div>
    </div>
@endsection