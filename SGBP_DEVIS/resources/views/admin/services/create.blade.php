@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.services.index') }}" class="text-gray-600 hover:text-gray-800">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Nouveau service</h2>
                    <p class="text-xs text-gray-500">Ajouter un service affiché sur le site</p>
                </div>
            </div>
        </div>

        <div class="content-with-mobile-nav p-4 md:p-8 max-w-3xl mx-auto">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <ul class="text-red-700 text-sm list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du service <span class="text-red-500">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: Maçonnerie">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icône Material Icons</label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: foundation, plumbing, build...">
                        <p class="text-xs text-gray-400 mt-1">
                            Voir <a href="https://fonts.google.com/icons" target="_blank"
                                class="text-blue-500">fonts.google.com/icons</a>
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Format JPG, PNG ou WebP — Max 2 Mo</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description courte</label>
                    <input type="text" name="description_courte" value="{{ old('description_courte') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Résumé en une phrase (affiché sur la landing page)">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description complète <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                        placeholder="Description détaillée du service">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                        <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="actif" value="0">
                            <input type="checkbox" name="actif" value="1"
                                {{ old('actif', '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700">Actif (visible sur le site)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.services.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Annuler</a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                        <span class="material-icons">save</span> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
