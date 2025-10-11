@php
    $isEdit = isset($article) && $article->exists;
@endphp

<form action="{{ $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store') }}" method="POST" id="articleForm" class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <!-- Type -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h3 class="text-xl font-bold mb-4">Type d'article</h3>
        @if($isEdit)
            <p class="text-sm text-gray-600">{{ $article->type_display }} (non modifiable)</p>
            <input type="hidden" name="type" value="{{ $article->type }}">
        @else
            <div class="grid md:grid-cols-2 gap-4">
                <label class="p-5 border-2 {{ old('type', 'produit') === 'produit' ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }} rounded-xl cursor-pointer">
                    <input type="radio" name="type" value="produit" {{ old('type', 'produit') === 'produit' ? 'checked' : '' }} class="sr-only" onchange="toggleType()">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-2xl">shopping_cart</span>
                        </div>
                        <div><p class="font-bold text-lg">Produit</p><p class="text-sm text-gray-600">Article stockable</p></div>
                    </div>
                </label>
                <label class="p-5 border-2 {{ old('type') === 'service' ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }} rounded-xl cursor-pointer">
                    <input type="radio" name="type" value="service" {{ old('type') === 'service' ? 'checked' : '' }} class="sr-only" onchange="toggleType()">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="material-icons text-gray-600 text-2xl">work</span>
                        </div>
                        <div><p class="font-bold text-lg">Service</p><p class="text-sm text-gray-600">Prestation</p></div>
                    </div>
                </label>
            </div>
        @endif
    </div>

    <!-- Basic Info -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h3 class="text-xl font-bold mb-4">Informations de base</h3>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-2">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $isEdit ? $article->nom : '') }}" required class="w-full px-4 py-3 border rounded-lg @error('nom') border-red-500 @enderror">
                @error('nom')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 border rounded-lg">{{ old('description', $isEdit ? $article->description : '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Référence (optionnel)</label>
                <input type="text" name="reference" value="{{ old('reference', $isEdit ? $article->reference : '') }}" class="w-full px-4 py-3 border rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour génération auto (ART-2026-0001)</p>
            </div>
        </div>
    </div>

    <!-- Catégories -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h3 class="text-xl font-bold mb-4">Catégories (optionnel)</h3>
        <div class="grid md:grid-cols-3 gap-3">
            @foreach($categories as $cat)
                <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer hover:bg-blue-50">
                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                        {{ (old('categories') && in_array($cat->id, old('categories'))) || ($isEdit && $article->categories->contains($cat->id)) ? 'checked' : '' }}
                        class="w-5 h-5">
                    <span class="material-icons text-sm">{{ $cat->icone ?? 'category' }}</span>
                    <span class="text-sm font-medium">{{ $cat->nom }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Pricing -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h3 class="text-xl font-bold mb-4">Tarification</h3>
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium mb-2">Unité <span class="text-red-500">*</span></label>
                <select name="unite" required class="w-full px-4 py-3 border rounded-lg">
                    <option value="">Sélectionner...</option>
                    <optgroup label="Quantité">
                        <option value="Unité" {{ old('unite', $isEdit ? $article->unite : '') === 'Unité' ? 'selected' : '' }}>Unité (U)</option>
                        <option value="Sac" {{ old('unite', $isEdit ? $article->unite : '') === 'Sac' ? 'selected' : '' }}>Sac</option>
                        <option value="Carton" {{ old('unite', $isEdit ? $article->unite : '') === 'Carton' ? 'selected' : '' }}>Carton</option>
                    </optgroup>
                    <optgroup label="Poids">
                        <option value="Kg" {{ old('unite', $isEdit ? $article->unite : '') === 'Kg' ? 'selected' : '' }}>Kilogramme (Kg)</option>
                        <option value="Tonne" {{ old('unite', $isEdit ? $article->unite : '') === 'Tonne' ? 'selected' : '' }}>Tonne (T)</option>
                    </optgroup>
                    <optgroup label="Temps">
                        <option value="Heure" {{ old('unite', $isEdit ? $article->unite : '') === 'Heure' ? 'selected' : '' }}>Heure</option>
                        <option value="Jour" {{ old('unite', $isEdit ? $article->unite : '') === 'Jour' ? 'selected' : '' }}>Jour</option>
                        <option value="Forfait" {{ old('unite', $isEdit ? $article->unite : '') === 'Forfait' ? 'selected' : '' }}>Forfait</option>
                    </optgroup>
                    <optgroup label="Surface">
                        <option value="M²" {{ old('unite', $isEdit ? $article->unite : '') === 'M²' ? 'selected' : '' }}>Mètre carré (M²)</option>
                        <option value="M" {{ old('unite', $isEdit ? $article->unite : '') === 'M' ? 'selected' : '' }}>Mètre (M)</option>
                    </optgroup>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Prix HT <span class="text-red-500">*</span></label>
                <input type="number" name="prix_ht" id="prix_ht" value="{{ old('prix_ht', $isEdit ? $article->prix_ht : '') }}" step="0.01" min="0" required class="w-full px-4 py-3 border rounded-lg">
            </div>
        </div>
    </div>

    <!-- Options -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <h3 class="text-xl font-bold mb-4">Options</h3>
        <div class="space-y-4">
            <label class="flex items-start gap-3 p-4 bg-blue-50 rounded-lg border-2 border-blue-200 cursor-pointer">
                <input type="checkbox" name="prix_modifiable" value="1" {{ old('prix_modifiable', $isEdit ? $article->prix_modifiable : true) ? 'checked' : '' }} class="w-5 h-5 mt-0.5">
                <div><span class="font-bold">Prix modifiable</span><p class="text-sm text-gray-600">Permettre ajustement dans devis</p></div>
            </label>
            @if($isEdit)
                <label class="flex items-start gap-3 p-4 bg-green-50 rounded-lg border-2 border-green-200 cursor-pointer">
                    <input type="checkbox" name="actif" value="1" {{ old('actif', $article->actif) ? 'checked' : '' }} class="w-5 h-5 mt-0.5">
                    <div><span class="font-bold">Article actif</span><p class="text-sm text-gray-600">Disponible dans devis/factures</p></div>
                </label>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between gap-3 sticky bottom-0 bg-white p-6 rounded-xl shadow-lg border-2">
        <a href="{{ route('admin.articles.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
            <span class="material-icons">close</span> Annuler
        </a>
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <span class="material-icons">{{ $isEdit ? 'save' : 'check_circle' }}</span> {{ $isEdit ? 'Modifier' : 'Enregistrer' }}
        </button>
    </div>
</form>

<script>
function toggleType() {
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        const label = radio.closest('label');
        if (radio.checked) {
            label.classList.add('border-blue-600', 'bg-blue-50');
            label.classList.remove('border-gray-300');
        } else {
            label.classList.remove('border-blue-600', 'bg-blue-50');
            label.classList.add('border-gray-300');
        }
    });
}
</script>