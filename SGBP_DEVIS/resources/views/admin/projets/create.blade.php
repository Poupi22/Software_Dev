@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projets.index') }}" class="text-gray-600 hover:text-gray-800">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Nouveau projet</h2>
                    <p class="text-xs text-gray-500">Ajouter une réalisation affichée sur le site</p>
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

            <form action="{{ route('admin.projets.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre du projet <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" value="{{ old('titre') }}" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: Villa résidentielle Libreville">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <input type="text" name="client_nom" value="{{ old('client_nom') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Nom du client">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                        <input type="text" name="lieu" value="{{ old('lieu') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: Libreville, Akanda">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="categorie"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Sélectionner...</option>
                            <option value="résidentiel" {{ old('categorie') == 'résidentiel' ? 'selected' : '' }}>Résidentiel</option>
                            <option value="commercial" {{ old('categorie') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="rénovation" {{ old('categorie') == 'rénovation' ? 'selected' : '' }}>Rénovation</option>
                            <option value="construction neuve" {{ old('categorie') == 'construction neuve' ? 'selected' : '' }}>Construction neuve</option>
                            <option value="industriel" {{ old('categorie') == 'industriel' ? 'selected' : '' }}>Industriel</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                        <input type="number" name="annee" value="{{ old('annee', date('Y')) }}" min="1900" max="2100"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durée</label>
                        <input type="text" name="duree" value="{{ old('duree') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: 18 mois">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Superficie</label>
                        <input type="text" name="superficie" value="{{ old('superficie') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Ex: 2 500 m²">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                        placeholder="Description du projet et des travaux réalisés">{{ old('description') }}</textarea>
                </div>

                {{-- ── SECTION MULTI-PHOTOS ── --}}
                <div class="border-2 border-dashed border-blue-300 rounded-xl p-6 bg-blue-50">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-icons text-blue-600 text-3xl">photo_library</span>
                        <div>
                            <h4 class="font-bold text-gray-800">Photos du projet</h4>
                            <p class="text-xs text-gray-500">Maximum 10 photos — JPG, PNG ou WebP — Max 3 Mo par photo</p>
                        </div>
                    </div>

                    <!-- Zone de drop / sélection -->
                    <label for="photos-input" id="photos-label"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50 transition mb-4">
                        <span class="material-icons text-blue-400 text-3xl mb-1">add_photo_alternate</span>
                        <p class="text-sm text-gray-600">Cliquez pour ajouter des photos</p>
                        <p class="text-xs text-gray-400" id="photos-label-hint">Sélection multiple autorisée (max 10)</p>
                        <input id="photos-input" type="file" name="photos[]" multiple accept="image/*"
                            class="hidden" onchange="addPhotos(this)">
                    </label>

                    <!-- Prévisualisation avec suppression -->
                    <div id="photos-preview" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3"></div>

                    <!-- Compteur -->
                    <p id="photos-count" class="text-xs text-gray-500">0 / 10 photos sélectionnées</p>
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
                    <a href="{{ route('admin.projets.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Annuler</a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                        <span class="material-icons">save</span> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var selectedFiles = []; // Tableau accumulatif des fichiers
        var MAX_PHOTOS = 10;

        // ── AJOUTER des photos (accumulation, pas remplacement) ────────────
        function addPhotos(input) {
            var newFiles = Array.from(input.files);
            var remaining = MAX_PHOTOS - selectedFiles.length;

            if (remaining <= 0) {
                alert('⚠️ Vous avez déjà atteint la limite de ' + MAX_PHOTOS + ' photos.');
                input.value = '';
                return;
            }

            if (newFiles.length > remaining) {
                newFiles = newFiles.slice(0, remaining);
                alert('⚠️ Seules ' + remaining + ' photo(s) supplémentaire(s) ont été ajoutées (limite de ' + MAX_PHOTOS + ' atteinte).');
            }

            // Ajouter les nouveaux fichiers au tableau accumulatif
            newFiles.forEach(function(f) { selectedFiles.push(f); });

            // Réinitialiser l'input pour permettre de re-sélectionner les mêmes fichiers
            input.value = '';

            refreshPreview();
            syncInputFiles();
        }

        // ── SUPPRIMER une photo par son index ─────────────────────────────
        function removePhoto(index) {
            selectedFiles.splice(index, 1);
            refreshPreview();
            syncInputFiles();
        }

        // ── SYNCHRONISER le vrai input file avec selectedFiles ────────────
        function syncInputFiles() {
            var dt = new DataTransfer();
            selectedFiles.forEach(function(f) { dt.items.add(f); });
            document.getElementById('photos-input').files = dt.files;
        }

        // ── RAFRAÎCHIR la prévisualisation ────────────────────────────────
        function refreshPreview() {
            var preview = document.getElementById('photos-preview');
            var counter = document.getElementById('photos-count');
            var hint    = document.getElementById('photos-label-hint');

            preview.innerHTML = '';
            counter.textContent = selectedFiles.length + ' / ' + MAX_PHOTOS + ' photos sélectionnées';

            var remaining = MAX_PHOTOS - selectedFiles.length;
            if (remaining > 0) {
                hint.textContent = 'Vous pouvez encore ajouter ' + remaining + ' photo(s)';
                document.getElementById('photos-label').style.display = '';
            } else {
                hint.textContent = 'Limite de ' + MAX_PHOTOS + ' photos atteinte';
                document.getElementById('photos-label').style.display = 'none';
            }

            selectedFiles.forEach(function(file, index) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'relative group rounded-lg overflow-hidden border-2 ' + (index === 0 ? 'border-blue-500' : 'border-gray-200');
                    div.id = 'photo-new-' + index;
                    div.innerHTML =
                        '<img src="' + e.target.result + '" class="w-full h-24 object-cover">' +
                        // Badge principale
                        (index === 0 ? '<span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded flex items-center gap-0.5"><span class="material-icons text-xs">star</span> Principale</span>' : '') +
                        // Bouton supprimer (toujours visible sur mobile, hover sur desktop)
                        '<button type="button" onclick="removePhoto(' + index + ')" ' +
                        'class="absolute top-1 right-1 w-7 h-7 bg-red-600 text-white rounded-full flex items-center justify-center shadow hover:bg-red-700 transition" ' +
                        'title="Retirer cette photo">' +
                        '<span class="material-icons text-sm">close</span>' +
                        '</button>';
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
