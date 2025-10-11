@php
    $isEdit = isset($category) && $category->exists;
@endphp

<form action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <span class="material-icons text-purple-600">category</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Informations</h3>
                <p class="text-sm text-gray-600">Détails de la catégorie</p>
            </div>
        </div>

        <div class="space-y-5">
            <!-- Nom -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la catégorie <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nom" 
                    value="{{ old('nom', $isEdit ? $category->nom : '') }}" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror"
                    placeholder="Ex: Maçonnerie, Plomberie..."
                >
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea 
                    name="description" 
                    rows="4" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Description de la catégorie..."
                >{{ old('description', $isEdit ? $category->description : '') }}</textarea>
            </div>

            <!-- Icône et Couleur -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Icône Material
                    </label>
                    <input 
                        type="text" 
                        name="icone" 
                        value="{{ old('icone', $isEdit ? $category->icone : 'category') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="category"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Voir: <a href="https://fonts.google.com/icons" target="_blank" class="text-blue-600 hover:underline">Material Icons</a>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Couleur
                    </label>
                    <input 
                        type="color" 
                        name="couleur" 
                        value="{{ old('couleur', $isEdit ? $category->couleur : '#2563EB') }}" 
                        class="w-full h-12 border border-gray-300 rounded-lg cursor-pointer"
                    >
                </div>
            </div>

            <!-- Ordre (si édition) -->
            @if($isEdit)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ordre d'affichage
                    </label>
                    <input 
                        type="number" 
                        name="ordre" 
                        value="{{ old('ordre', $category->ordre) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0"
                    >
                    <p class="text-xs text-gray-500 mt-1">Plus petit = affiché en premier</p>
                </div>
            @endif

            <!-- Statut (si édition) -->
            @if($isEdit)
                <label class="flex items-center gap-3 p-4 bg-green-50 rounded-lg border-2 border-green-200 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="actif" 
                        value="1" 
                        {{ old('actif', $category->actif) ? 'checked' : '' }} 
                        class="w-5 h-5 text-green-600 border-gray-300 rounded"
                    >
                    <div>
                        <span class="font-bold text-gray-800">Catégorie active</span>
                        <p class="text-sm text-gray-600">Disponible pour les articles</p>
                    </div>
                </label>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between gap-4 sticky bottom-0 bg-white p-6 rounded-xl shadow-lg border-2 border-gray-200">
        <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition">
            <span class="material-icons">close</span> Annuler
        </a>
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition shadow-md">
            <span class="material-icons">{{ $isEdit ? 'save' : 'check_circle' }}</span> 
            {{ $isEdit ? 'Enregistrer' : 'Créer' }}
        </button>
    </div>
</form>