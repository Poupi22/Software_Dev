@extends('admin.layouts.app')
@section('content')
<div class="min-h-screen pb-20 bg-gray-50">
    <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.articles.index') }}" class="text-gray-600"><span class="material-icons">arrow_back</span></a>
                <div><h2 class="text-xl md:text-2xl font-bold">Modifier Article</h2><p class="text-sm text-gray-500">{{ $article->nom }}</p></div>
            </div>
            <span class="px-3 py-1 text-xs {{ $article->actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">{{ $article->actif ? 'Actif' : 'Inactif' }}</span>
        </div>
    </div>
    <div class="p-4 md:p-8 max-w-4xl mx-auto">
        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <ul class="text-sm text-red-600">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @include('admin.articles.form')
    </div>
</div>
@endsection