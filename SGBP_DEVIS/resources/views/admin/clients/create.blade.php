@extends('admin.layouts.app')
@section('content')
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .fade-in {
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- Main Content -->
    <div class="min-h-screen pb-20 bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Nouveau Client</h2>
                        <p class="text-xs md:text-sm text-gray-500">Créez une fiche client complète</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2">
                    <span class="material-icons text-green-600">check_circle</span>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700 font-medium flex items-center gap-2">
                    <span class="material-icons text-red-600">error</span>
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
            <div class="max-w-4xl mx-auto">
                @include('admin.clients.form')
            </div>
        </div>
    </div>

    <!-- Mobile FAB -->
    <button class="md:hidden fixed bottom-24 right-4 w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-full shadow-lg flex items-center justify-center z-20 hover:shadow-xl transition" onclick="document.getElementById('clientForm').submit()">
        <span class="material-icons">check</span>
    </button>
@endsection